import esbuild from 'esbuild'
import { readFileSync, writeFileSync, mkdirSync } from 'node:fs'

const breakpoints = { md: '768px', lg: '1024px', xl: '1280px' }
const columns = [2, 3]

const disabled = ':not(.fi-masonry-disabled, .no-masonry)'

// The grids a block applies to, for one breakpoint and one number of columns.
function grids(bp, n) {
    const cols = `[style*="--cols-${bp}: repeat(${n},"]`
    const full = `:not([style*="1 / -1"]):not([style*="span ${n} /"])`

    const explicit = `.fi-masonry > .fi-sc.fi-grid${cols}`

    const auto = [
        `:root:has(> head > .fi-masonry-config.fi-masonry-auto.fi-masonry-bp-${bp})`,
        `${disabled} > .fi-sc.fi-grid${disabled}${cols}`,
        ':has(> .fi-grid-col:not(.fi-hidden) > .fi-sc-component > .fi-sc-section)',
        `:not(:has(> .fi-grid-col:not(.fi-hidden)${full} > .fi-sc-component > :not(.fi-sc-section)))`,
    ]

    return { explicit, auto }
}

// Expands {X} and {X <suffix>} over the explicit and automatic selectors.
function expand(block, bp, n) {
    const { explicit, auto } = grids(bp, n)

    const [root, wrapper, has, not] = auto

    // Suffixes are appended to the grid itself, i.e. after the last compound
    // selector of the automatic form.
    return block
        .replaceAll('{bp}', bp)
        .replaceAll('{min}', breakpoints[bp])
        .replaceAll('{n}', String(n))
        .replace(/\{X([^}]*)\}/g, (_, suffix) => [
            explicit + suffix,
            `${root} ${wrapper}${has}${not}${suffix}`,
        ].join(',\n        '))
}

function render() {
    const source = readFileSync('./resources/css/masonry.css', 'utf8')
    const [head, rest] = source.split('/* @expand */')
    const [template] = rest.split('/* @end */')

    let css = ''

    for (const bp of Object.keys(breakpoints)) {
        for (const n of columns) {
            css += expand(template, bp, n)
        }
    }

    return head + css
}

mkdirSync('./resources/dist', { recursive: true })

const result = await esbuild.transform(render(), {
    loader: 'css',
    minify: !process.argv.includes('--dev'),
    target: ['chrome111', 'safari16.4', 'firefox121'],
})

// The banner comment is kept so that the shipped file says what it is.
writeFileSync(
    './resources/dist/filament-masonry.css',
    '/*! filament-masonry (SS2i Digital) - MIT */\n' + result.code,
)

console.log('Build completed for filament-masonry.css')
