init:
	docker-compose -f redmine.yml -f gitlab.yml -f application.yml  up -d

close:
	docker-compose -f redmine.yml -f gitlab.yml -f application.yml  down

close_and_remove_data:
	docker-compose -f redmine.yml -f gitlab.yml -f application.yml  down -v