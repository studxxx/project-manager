site:
	ansible-playbook -i hosts.yml site.yml
	#ansible-playbook -i hosts.yml site.yml -vvvv --extra-vars "ansible_sudo_pass="

renew-certificates:
	ansible-playbook -i hosts.yml renew-certificates.yml

authorize:
	ansible-playbook -i hosts.yml authorize.yml
	#ansible-playbook -i hosts.yml authorize.yml --extra-vars "ansible_sudo_pass="

generate-deploy-key:
	ssh-keygen -q -t rsa -N '' -f files/deploy_rsa

authorize-deploy:
	ansible-playbook -i hosts.yml authorize-deploy.yml
	#ansible-playbook -i hosts.yml authorize-deploy.yml --extra-vars "ansible_sudo_pass="

docker-login:
	ansible-playbook -i hosts.yml docker-login.yml
	#ansible-playbook -i hosts.yml docker-login.yml -vvvv --extra-vars "ansible_sudo_pass="

ping:
	ansible all -m ping -i hosts.yml

check:
	ansible-playbook --su --su-user=root --ask-su-pass playbook.yml
