build:
	docker build -t dboss .

run:
	docker run -p 127.0.0.1:8080:8080 -it --rm --name dboss-running dboss

shell:
	docker exec -it dboss-running /bin/sh

network-connect:
	docker network connect $(net) dboss-running
