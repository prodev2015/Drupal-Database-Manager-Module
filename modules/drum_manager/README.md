# README

## INTRODUCTION
Students Drupal 8 Example Module

## REQUIREMENTS
There are not any special requirements.

## INSTALLATION
The module "Students" must be installed and enabled in the Drupal.

## CONFIGURATION

AddForm
-------
- use FormBase
- change declaration buildForm, submitForm, validateForm
  (form_state is an object and no longer an array)
- no arguments passed by the buildForm declaration

DeleteForm
----------
- getCancelUrl in stead of getCancelRoute
- change declaration buildForm, submitForm, validateForm
  (form_state is an object and not array)
- no arguments passed by the buildForm declaration

AdminController
---------------
- content function returns array of 2 elements (text markup and a table)
- l-function has changed. 1 argument is now a URL object

Local Tasks
-----------
- use .links.task.yml
- use base_route: system.admin_content
