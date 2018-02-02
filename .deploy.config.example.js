module.exports = {
    settings: {
        "host": "your sftp host",
        "port": "your port",
        "username": "username",
        "password": "password"
    },
    queue: [
        "functions/**/*.*",
        "parts/**/*.*",
        "static/**/*.*",
        "functions.php",
        "index.php",
        "style.css",
        "screenshot.png"
    ],
    target: "/absolute/path/to/deploy/target"
}
