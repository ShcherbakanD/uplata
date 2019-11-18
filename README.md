REQUIREMENTS: <code>docker, docker-compose</code>

BUILD:
1.Copy .env.dist to .env <br>
2.Make docker-compose build <br>
3.Run postgres-create-tables.sh <postgres_containerid>, supervisord-start.sh <php_containerid> <br>
4.Start load integration cli.php app:load-integration <integration name>

COMMANDS cli.php:<br>
<code>1.app:load-integration - Start loading integration by config file in app/config/forums.</code><br>
<code>2.app:run-consumer <save_topic|save_comment> - Running consumer by name.</code>
