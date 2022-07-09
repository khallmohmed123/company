# Installation

- the company file of project must be in xampp > htdocs with the name of company for the application
- start project url http://localhost/company/views/ or http://localhost/company/
- config.php contains the database variables of string which used by classes to set up connections with data base and route controls and the pepper password hashing
  1.  **default host localhost in PARAM_HOST**
  2.  **default port 3306 in PARAM_PORT**
  3.  **database name company_list in PARAM_DB_NAME**
  4.  **user by default root in PARAM_DB_USER**
  5.  **password by default null '' in PARAM_DB_PASS**
  6.  **pepper password hashing which yellowpassword**
- to upload more than 20 photo at time you should edit php.ini max_file_uploads from 20 to the the limit should uploaded at time
- to upload files that exceed 40M should edit php.ini post_max_size from 40M to the the limit should uploaded files example 100M,1024M,1G,...
- the project create database and tables and areas and cities by default if this tables and database doesn't exists

## Sign up

- in sign up the user choose the account administrator or customer

## Login

- in the login detect the administrators and the customers accounts which gives them privileges on site functions

## Main View Of List Companies

- the top sidebar is contains the user mail and the account settings
  - **contains the user mail and the account settings which can update user info**
- companies table contain the images column that by default load the first image to list all company
  images click on the photo to open modal of all images with Carousel

# User Privileges

- all users can view the companies list
- the administrators user only can modify the companies list
  - add
  - edit
  - update
  - delete (delete all files on the company and company record in database)

# Add Category _(Administrators Role)_

- should add some categories to shown in select of company add
- add category name required to shown in company add section

# Add Company _(Administrators Role)_

- on add company button open modal with the required fields to add the new company
  - Name
  - the form allows multiple categories selection
    (to select more than one category should press ctrl button and click)
  - Phone number
  - City (witch by default Egyptian cities)
  - Areas (which changed based on the city choose)
  - file uploading allows multiple file uploading

# Edit Company _(Administrators Role)_

- on company edit you have full control
  - delete from old images to delete from old just hover image to show delete icon
  - upload new images
  - name
  - city
  - area
  - phone number
