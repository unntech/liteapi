

pid=$(cat http.pid)

case $1 in
	start)
		echo 'Start Service...'
		php httpapi.php
		;;
	stop)
		echo 'Stop Service...'
		kill -15 $pid
		;;
	reload)
		echo 'Reload Service...'
		kill -USR1 $pid
		;;
	retask)
		echo 'Reload Task...'
		kill -USR2 $pid
		;;
	restart)
		echo 'Stoping Service...'
		kill -15 $pid
		sleep 2
		echo 'Start Service...'
		php httpapi.php
		;;
	status)
		;;
esac



