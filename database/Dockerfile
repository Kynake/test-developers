FROM postgres:latest

COPY init.sql /docker-entrypoint-initdb.d/
# RUN psql -d devtest -U postgres -f /tmp/init.sql