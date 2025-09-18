aplikasi backend yang dibangun menggunakan Laravel 12 dan berfungsi sebagai sistem pengelolaan data absensi karyawan

## Installasi 

    - Clone Repository
    jalankan perinta berikut ini pada terminal kamu untuk clone project repository nya
    git clone https://github.com/dikialfin/fleetify-test.git

    - Install Dependensi
    jalankan perintah berikut ini pada terminal untuk menginstall dependensi
    composer install

    - Konfigurasi File ENV
    buat salinan file .env.example dan ubah nama salinan file tersebut menjadi .env, setelah itu sesuaikan konfigurasi database didalam file tersebut

    - Jalankan Migration Database
    jalankan perintah berikut ini pada terminal untuk menjalankan migration database
    php artisan migrate

    - Jalankan Seeder
    jalanakn perintah berikut ini pada terminal untuk menjalan seeder departement
    db:seed --class=DepartementSeeder

    - Menjalankan Server
    jjaalankan perintah berikut ini pada terminal untuk menjalankan server
    php artisan serve

## Endpoint

    - Employee
        GET "/employee"
        mendapatkan semua data employee

        GET "/employee/{id}"
        mendapatkan data employee berdasarkan employee_id nya

        POST "/employee"
        menambahkan data employee, membutuhkan data json body (employee_id,departement_id,name,address)

        PUT "/employee/{id}"
        mengubah detail data employee berdasarkan employee_id nya, membutuhkan data json body (employee_id,departement_id,name,address) kirimkan data json body untuk data yang ingin diubah saja

        DELETE "/employee/{id}"
        menghapus data employee berdasarkan employee_id nya


    - Departement
        GET "/departement"
        mendapatkan semua data departement

        GET "/departement/{id}"
        mendapatkan data departement berdasarkan id nya

        POST "/departement"
        menambahkan data departement, membutuhkan data json body (departement_name,max_clock_in_time,max_clock_out_time)

        PUT "/departement/{id}"
        mengubah detail data departement berdasarkan id nya, membutuhkan data json body (departement_name,max_clock_in_time,max_clock_out_time) kirimkan data json body untuk data yang ingin diubah saja

        DELETE "/departement/{id}"
        menghapus data departement berdasarkan employee_id nya


    - Attendance
        GET "/attendance"
        mendapatkan semua data attendance, optional data json body (departement_id,date) untuk filter berdasarkan tanggal dan departement

        POST "/clock-in"
        menambahkan data clockin, membutuhkan data json body (employee_id,clock_in_time)

        PUT "/clock-out/{id}"
        menambahkan data clockout berdasarkan id attendance, membutuhkan data json body (clock_out_time)
