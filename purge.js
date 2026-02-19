import { PurgeCSS } from 'purgecss'
import fs from 'fs'

const purgeCSSResult = await new PurgeCSS().purge({
  content: ['./resources/views/**/*.blade.php', './public/js/**/*.js'],
  css: ['./public/css/style.css'],
  safelist: [/^modal/, /^show/, 'active']
})

fs.mkdirSync('./dist/css', { recursive: true })
fs.writeFileSync('./dist/css/purged.css', purgeCSSResult[0].css)

console.log('✅ Purged CSS created at dist/css/purged.css')

