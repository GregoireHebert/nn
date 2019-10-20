# Tamagotchi example of nn usage

This sheep needs to eat, sleep, run.
For each need, being over 7/10, has in effect to remove 1/10 life point.
The Neural Network is train so it does not happen.

Every 20sec, a new action is taken.

## Quick start

### start the webserver
```bash
./start
```

open you browser to http://localhost:8000

### stop the web server

```bash
./stop
```

## Installation

```bash
composer install
cd public && yarn install && yarn run build
```

## Run locally

```bash
symfony server:start -d --no-tls
symfony server:stop # stop the server
```

you can now launch your navigator and open the http://localhost:8000

```bash
$ chromium-browser --start-fullscreen --start-maximized --no-default-browser-check --incognito http://localhost:8000 &>/dev/null &
```

## Run on background for raspberry

to run the all thing in the background you'll need supervisor

```shell
$ sudo apt install supervisor
```

Create a configuration file for the program

```ini
# /etc/supervisor/conf.d/tamagotchi.conf
[program:tamagotchi]

# change this with your username
user=$(whoami)

# change according to your path
command=/srv/start

# uncomment these lines to launch this at start
#autostart=true
#autorestart=true

# change according to your path
stdout_logfile=/srv/var/logs/supervisor_stdout.log
stderr_logfile=/srv/var/logs/supervisor_stderr.log
```

Then you must tell supervisor to get and load this new config.

```shell
$ supervisorctl reread
$ supervisorctl update
$ supervisorctl
tamagotchi                       STOPPED   Jan 14 02:33 PM
supervisor>

#Now, we can start, stop and restart the tamagotchi:

supervisor> stop tamagotchi
tamagotchi: stopped

supervisor> start tamagotchi
tamagotchi: started

supervisor> restart tamagotchi
tamagotchi: stopped
tamagotchi: started

# You may also view the most recent entries from stdout and stderr logs using tail command:

supervisor> tail tamagotchi
[OK] Web server listening on http://127.0.0.1:8000

supervisor> tail tamagotchi stderr

# Checking program status after making changes is easy as well:

supervisor> status
tamagotchi                       RUNNING   pid 26039, uptime 0:00:24
```

## debug

```bash
symfony server:status # check the server running
symfony server:log # to display the logs
```

## troubleshouting

## supervisor error

`error: <class 'socket.error'>, [Errno 13] Permission denied: file: /usr/lib/python2.7/socket.py line: 228`

if you got this message when lauching `supervisorctl` command,
update `/etc/supervisor/supervisor.conf` and change the `unix_http_server` chmod to `766`

### reset the tamagotchi.

The sheep id is stored in the session.
delete the session cookie to create a new sheep.
