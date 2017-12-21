const deploy = require('fh-deploy').default
const path = require('path')

deploy(path.resolve('./', '.deployrc'))
