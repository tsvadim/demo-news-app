require 'yaml'
require 'fileutils'

required_plugins = %w( vagrant-hostmanager vagrant-vbguest vagrant-bindfs )
required_plugins.each do |plugin|
    exec "vagrant plugin install #{plugin}" unless Vagrant.has_plugin? plugin
end

domains = {
  frontend: 'basic-app.test',
  backend:  'adm.basic-app.test'
}

bind_folders = {
  map:'./',
  to:'/app'
}


config = {
  local: './vagrant/config/vagrant-local.yml',
  example: './vagrant/config/vagrant-local.example.yml'
}

# copy config from example if local config not exists
FileUtils.cp config[:example], config[:local] unless File.exist?(config[:local])
# read config
options = YAML.load_file config[:local]

# set config domain name
domains = options['domains']
# domains[:frontend] = options['domains']['frontend']
# domains[:backend] = options['domains']['backend']

# check github token
if options['github_token'].nil? || options['github_token'].to_s.length != 40
  puts "You must place REAL GitHub token into configuration:\n/yii2-app-advanced/vagrant/config/vagrant-local.yml"
  exit
end

# print options['domains']['frontend']
# print domains['frontend'] +" | "+ domains['backend']


# vagrant configurate
Vagrant.configure(2) do |config|
  # select the box
  config.vm.box = 'bento/ubuntu-16.04'

  # These values are the default options
  config.bindfs.default_options = {
    force_user:   'vagrant',
    force_group:  'vagrant',
    perms:        'u=rwX:g=rD:o=rD'
  }

  # should we ask about box updates?
  config.vm.box_check_update = options['box_check_update']

  config.vm.provider 'virtualbox' do |vb|
    # machine cpus count
    vb.cpus = options['cpus']
    # machine memory size
    vb.memory = options['memory']
    # machine name (for VirtualBox UI)
    vb.name = options['machine_name']
  end

  # machine name (for vagrant console)
  config.vm.define options['machine_name']

  # machine name (for guest machine console)
  config.vm.hostname = options['machine_name']

  # network settings
  config.vm.network 'private_network', ip: options['ip']
  # localtunel port
  config.vm.network :forwarded_port, guest: 80, host: 8888

  # sync: folder 'web-app' (host machine) -> folder '/app' (guest machine)
  config.vm.synced_folder './', '/app', type: :nfs

  # Use vagrant-bindfs to re-mount folder
  config.bindfs.bind_folder '/app', '/app'

  # Default config   config.vm.synced_folder './', '/app', owner: 'vagrant', group: 'vagrant'

  # disable folder '/vagrant' (guest machine)
  config.vm.synced_folder '.', '/vagrant', disabled: true

  #enable simlink on host mashine
  config.vm.provider "virtualbox" do |v|
    v.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate/vagrant", "1"]
		v.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate/app", "1"]
	end

  # hosts settings (host machine)
  config.vm.provision :hostmanager
  config.hostmanager.enabled            = true
  config.hostmanager.manage_host        = true
  config.hostmanager.ignore_private_ip  = false
  config.hostmanager.include_offline    = true
  config.hostmanager.aliases            = domains.values

  # provisioners
  config.vm.provision 'shell', path: './vagrant/provision/once-as-root.sh', args: [options['timezone'], domains['frontend'], domains['backend'],options['system_locale']]
  config.vm.provision 'shell', path: './vagrant/provision/once-as-vagrant.sh', args: [options['github_token'], domains['frontend'], domains['backend'],options['admin_pass']], privileged: false
  config.vm.provision 'shell', path: './vagrant/provision/always-as-root.sh', args: [domains['frontend'], domains['backend']], run: 'always'

  # post-install message (vagrant console)
  # config.vm.post_up_message = "Frontend URL: http://#{domains[:frontend]}\nBackend URL: http://#{domains[:backend]}"
  config.vm.post_up_message = "Frontend URL: http://#{domains['frontend']}\nBackend URL: http://#{domains['backend']}\nIP:#{options['ip']}\nDatabase:\n Remote host:#{options['ip']}:3306\n Login:root\n Password: EMPTY PASSWORD\n Root access:\n Login:vagrant\n Password: vagrant\nAdmin user:\n Login:admin\n Password:#{options['admin_pass']}"

end
