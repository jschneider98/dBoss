build:
	docker build -t dboss .

run:
	docker run -it \
	-p 127.0.0.1:8080:8080 \
	-v db:/usr/src/dboss/data/db \
	--rm \
	--name dboss-running dboss

shell:
	docker exec -it dboss-running /bin/sh

# docker network ls
# make network-connect net=<network name>
network-connect:
	docker network connect $(net) dboss-running
