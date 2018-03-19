### Setup Using Docker

Docker setup definitions:

- github_repo_link: git@github.com:best-line/bestline.git
- local_folder_name: We recommend you create a folder *in your user folder* with the name bestline
- docker_image_names: bl-php-image, bl-mysql-image
- docker_container_names: bl-php-container, bl-mysql-container
- docker_service_names: bl-php-service, bl-mysql-service
- local_development_url:  http://[docker machine's ip address]:8000/ (e.g. [http://192.168.99.100:8000/](http://192.168.99.100:8000/))
- containers_finished_running_string: "Bestline is ready!"

Setup Steps:

1. Install [GIT](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git) on your local machine if you don't already have it
1. Clone this repository to your computer
    1. Create a folder (e.g. local_folder_name) in your user directory
        - Note: especially for windows users, it is very important that the folder is in your computer's USER directory!
    1. open up a terminal and navigate to the new folder (e.g. `cd your_user_folder/www/local_folder_name/`)
    1. run `git clone github_repo_link .`
1. Install [Docker Toolkit](https://docs.docker.com/engine/installation/)
    - Note: these instructions are written assuming you have "Docker Toolkit" and not docker for Windows or Mac
    - Make sure to install [VirtualBox](https://www.virtualbox.org/wiki/Downloads) as part of the docker tool box install (unless it is already installed on your machine). It is important that your local environment is run on a virtual machine.
1. Open the "Docker Quickstart Terminal"
    - You should see a docker whale at the top of the terminal where you can type commands
        - Note: you may want to note your docker machine's IP address (e.g. 192.168.99.100)
    - Navigate your computers file system to the cloned repository folder (created above) (e.g. `cd YOUR_USER_FOLDER/www/local_folder_name/`)
1. Magically create all the docker containers needed to run the code
    - In the quick start terminal run `docker-compose up`
    - You should expect it print a lot to the console while it...
        1. builds your docker services (and by extension your images)
        1. starts your containers
        1. runs the entrypoint files for all services
    - Containers are not ready till you see your containers_finished_running_string (see above)
        - You may have to search this string in your console from time to time just to make sure it is done
        - If you ever see a string similar to "docker_container_names exited with code 0" then something went wrong
1. View your site's web address (local_development_url)
    1. Open Google Chrome (or another web browser)
    1. Visit your local_development_url
    1. Expect to see a login form

##Trouble shooting your docker containers:

1. For trouble shooting docker see this [troubleshooting guide](https://github.com/bbuie/code_snipits/wiki/Docker-Trouble-Shooting)
    - You'll need to note some of the setup definitions noted above

##Helpful docker commands

- `docker-compose up --build`
    - Rebuild all services in the docker-compose.yml file
- `docker-compose down`
    - Shut down all services in the docker-compose.yml file
- `docker exec -it [docker_container_names] bash`
    - Connect to a running container
    - Note, the container must be running!
- `docker run --rm -it [docker_image_names]`
    - Connect to an image
- `docker-machine restart`
    - Restart the default docker machine
    - This is helpful for resetting networks and otherwise refreshing stuff
- `docker rmi $(docker images -a --filter=dangling=true -q)`
    - Clean up all dangling (unused) docker images
- `docker rm $(docker ps --filter=status=exited --filter=status=created -q)`
    - Clean up all exited containers
- `docker volume rm $(docker volume ls -q -f dangling=true)`
    - Clean up all dangling (unused) volumes
