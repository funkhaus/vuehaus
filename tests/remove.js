/* eslint no-console: 0 */

const rimraf = require('rimraf')
rimraf.sync('tests/output-*')
console.log('Removed test output directories!')
