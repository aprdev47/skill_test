# History

You are a software developer responsible for the code for a company (AwesomeCorp) that, like any good company, records the details of their employees. AwesomeCorp has developed an internal system to keep track of their employees. 

This system was originally built in 2007 and has gone through many iterations and different developers over the years. 

Originally, in 2007 employees could "self-register" via a web UI when they joined AwesomeCorp.

 - Accessible via `/web_app/new.php`
 - This allows the employee to enter their details, and choose their own password.
 - The employee gets re-direct the employee to a dashboard page once they have registered. `/web_app/dashboard.php`

In 2012, AwesomeCorp grew, and started recruiting staff through a 3rd party company, RecruiterForce (in addition to their normal way). This meant that RecruiterForce's systems needed a way to pass details to AwesomeCorp. The developers at AwesomeCorp developed an API for this to happen.

 - The API endpoint is: [root]/api/new.php
 - Because this is a process that happens "behind the scenes" - the employee's password is automatically generated.

In both ways of creating an employee, for every employee added to AwesomeCorp's system:

 - There is an email sent to the employee, advising them where they can login, and their password
 - There is an audit log entry created for AwesomeCorp's records.


# Scenario
Like any company - AwesomeCorp's code has some problems! - as the software developer, it is your job to solve these problems for them. It will also be your job to maintain and manage this code in future.  

## The Problems
 - AwesomeCorp's software has been running on PHP 5.3 for a while. However, their server provider is forcing them to upgrade to PHP 7.1. This means that the PHP 5.3 methods 'mysql_connect' (and similar) will no longer be available. You need to make sure that their code will still function after this upgrade.
 - AwesomeCorp needs to store new data for employee gender (male or female). In the near future, they will be adding even more fields.
 - Due to new regulations, AwesomeCorp needs to provide a report to the government about all of their new employees. Luckily, another team will take care of actually passing the report content, but they have requested a file on the server, in /tmp/employee_report.csv that contains CSV data of all newly created employees, in the format of: `[employee id],[employee name],[employee email]`. This file should get a new line added every time there is a new employee created.

## The Rules
 - Please limit your time on this to two hours or less.
 - It's okay to refactor - in fact, we _encourage_ refactoring to make it easier to work with the code in future. 
 - Please fork this repository, and create a PR into your forked repo, send us the link to the PR.
 - Feel free to provide diagrams or any documentation, on the PR.
 - **We do not mind if you run out of time, or cannot solve the problems**. However, where you have not solved the problems, we expect you to tell us:
     - How you _would_ have liked to solve the problem.
     - What challenges you faced when deciding on a solution.
     - Any additional information you would like to know to do a better job.
  
# Tech Stuff 
- This code should run on php 5.x. 
- You can find the database schema in `assets/db.sql`.
- The code assumes (in a few places) that the db is hosted on "localhost", with "root" user and no password. The db name is assumed to be "test". Feel free to change this to suit if required.
- For simplicity, the API request is mocked in `api/new.php` with an array and a function.
- You should be able to visit `web_app/new.php` in your browser and be able to register a new employee.
- You should be able to visit `api/new.php` and have the page load register a new employee.



