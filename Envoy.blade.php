@servers(['web' => 'ofaasd@103.187.147.220'])

@task('deploy')
    sudo su
    cd /var/www/stifar.id/public_html/
    git pull origin main

    cd /var/www/mhs.stifar.id/public_html/
    git pull origin main

    cd /var/www/dsn.stifar.id/public_html/
    git pull origin main

@endtask
