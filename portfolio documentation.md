## Drupal Portfolio Documentation

# Installations needed (windows virtual machine ON)
Docker Desktop / Composer installed
DDEV installed
Choco installed
VSCode (required) or similar code editor

After installation, make sure that Docker with it containers are running


# Setting up DDEV environment

- In a Local environment:
. Create the Project directory in your local machine
. Change Dir  (cd) to the project folder
. Initialize DDEV (ddev config) - provide the Project name, docroot location and the project type.
. Start the project (ddev start)
. Installation of the dependancies (ddev composer install) and of Drush (ddev composer require drush/drush)
. Installation of Drupal: you can use ddev drush or proceed with installation on the browser
. Setup files permission if your user doesn't have it (ddev exec chmod -R 775 web/sites/default/files)
. Clear the cache by runing ddev drush cr the ddev launch to launch the site


- Deploying a Project locally from a reomte repository:
. Clone the project on github using git clone <repository-url>
. Initialize DDEV inside your project Dir. with ddev config (this will ask you to provide the Project name, docroot location and the project type)
. Start DDEV using ddev start
. Import the Database using ddev import-db --src=database-file-name.sql.gz
. Install Dependencies by running ddev composer install
. Run a db updates (optional) using ddev drush updb -y
. Clear cache using ddev drush cr

To ACCESS your local site visit the project url in the browser. Get the description by running ddev describe
. To access the Drupal admin run: ddev drush uli (to get a one-time login link)
. Run ddev restart to restart your DDEV project



## Key Customizations

# Theme customisation: the Base Theme (Olivero)
    Created a new theme named portfolio
     added the .info.yml file (the required configeration file for Drupal theme creation, containing the theme name - description - base theme - regions - libraries ...)
     the .libraries.yml file holds all the CSS/sass - js libraries that work to customize/override the global styling and behavior

# Module creation
    created a portfolio block custom module
    added a portfolio_block.info.yml file as the required configiration file fo the custom module
    It helps add a custom block to display the latest 3 Portfolio items dynamically
    Also created a PortfolioBlock.php where we provided the namespaces - class - methods... that help the custom block to display the latest 3 Portfolio items dynamically

# Used twig templates:
    node.html.twig - to override Olivero's theme way to display a node
    page.html.twig - to override the olivero base theme way of displaying a page

# Used CSS files:
    to help override the base theme global stylings from the header - body/content - to the footer
  
