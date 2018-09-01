<h2> <strong> Classeventie V2 </strong> </h2>

Classeventie is a multi-purpose classes and events booking system. Classeventie comes with a beautifull frontend UI where 
users can easily book events and classes and pay online. All the events and classes bookings can be managed via backend.

Classeventie has features like recursive classes and events, classes and events which repeats weekly or monthly. It has 
blog management, content management, taxes management and much more.

You can use it for your institute, gym, any type of event organising company, paid hobby classes, summer camps or
for any other such types of fields. 

E.g 1- If you are an owner of an Institute in which you run classes for yoga, dance, music, etc Then Classeventie will definitely help you grow and scale your business online.

E.g 2- If you are an Event Organizer and you organize events in which you allow users to make payment online and provide tickets online. Then Classeventie is perfectly for you.

Using classeventie, you can organize paid or free - 
workshops, annual day, freshers day, repetitive events like weekly or monthly tests, music concerts, dance shows and much more.


<h2> <strong> FEATURES </strong> </h2>

- Repetitive Classes

- Repetitive Events

- Multiple days Events

- Multiple days Classes

- N level categories

- Manage courses, batches, events, tutors, blogs, custom pages

- Group Booking

- Daily, weekly and monthly calendar

- Booking Email notification 

- New Booking notification 

- Manage invoices, taxes, sales reports

- Paypal & Stripe Payment Gateways

- Multi Currency & Timezones

- Mobile optimized & fully responsive 

- Beautiful UX & UI

- Google & Facebook login 

- Multi-Language & RTL support 

- Multi-User Groups with ACL management 

- 100+ Admin Settings for full control 

- 20 Multi-Color Material Themes 

- Super Easy 1-Click installation 


<h2> <strong>  More Features </strong> </h2>

- <strong> Easy Booking Management </strong>
Create invoices for bookings, export them to CSV and excels. Also, manage booking cancellations with one click.


- <strong> Blogs & Notifications Management </strong>
Automatic notification generation on every new entry and manage customers blogs, approve them and publish them.


- <strong> Know your business better </strong>
Take online payments and run your business on the go and then see how it all adds up to site visitors and sales graph analytics.


- <strong> Manage Users Easily </strong>
This website contains the most secured Ion-Auth user login system with custom ACL management system to control the permissions of multiple user groups.


- <strong> 100+ Settings To Fully Control Every Feature </strong>
Manage each and every important feature with about 100+ settings options for an easy and centralized control system.


- <strong> Premium Material Themes Integration </strong>
Front-end contains Universh Premium Material Theme whereas Back-end contains AdminBSB Material Theme.


- <strong> Easy 1-Click Installation </strong>
Just copy the product folder to your server enter – http://localhost/example/install OR http://example.com/install .


- <strong> Single & Group Booking </strong>
Customers can book seats on their own as well as for their friends (multiple members) at a time. Even they can cancel their booking before pre-cancellation time as well.


- <strong> Add Courses & Batches </strong>
Add N-Levels of categories and courses and their multiple batches.


- <strong> Add Recurring Batches & Events </strong>
You can add Recurring Batches and Events which repeats each month/year/week.


- <strong> Featured Listings </strong>
Add and manage courses and events featured listings.


- <strong> DISQUS integration </strong>
Integrated Disqus Commenting System so that customers can easily discuss events and batches prior to booking. This will make customers buy happily.




<h2> INSTALLATION </h2>
<strong>Automatic 1-Click Installation</strong>
<pre>

1. Extract and Copy the product's folder 'classeventie' to your server/localhost www/htdocs folder.

2. Enter URL http://localhost/classeventie/install or http://example.com/install.

3. Then enter your database details and click install. 
  (If any empty page or any error page shown after successful installation, then just hit refresh)

4. Use the below credentials for admin login -
  Username : admin 
  Password : admin123

5. After successfully logging in you will see admin panel link in the Header->User dropdown
 </pre>


<strong>Manual Installation</strong>
<pre>

1. Copy the product's folder "classeventie" to your server/localhost www/htdocs folder.

2. Create a new database from Mysql Host (PHPMyAdmin or any other) and import database file from     
    --> classeventie/install/assets/install.sql
   
    **If in case of database errors, then you Mysql version is old, but we have a compatible DB for the older version-**   
     --> classeventie/install/assets/install_old_mysql_version.sql

3. Then after creating and importing database successfully, update the database details in-
    --> application/config/development/database.php (according to local server)
    --> application/config/production/database.php file (according to online server)

4. Update the $config['base_url'] value in-
     --> application/config/development/config.php (e.g http://localhost/classeventie/)
     --> application/config/production/config.php (e.g http://example.com/)

5. At last use the below creds for admin login -
  Username : admin 
  Password : admin123

6. After successfully logging in you will see admin panel link in the Header->User dropdown.
</pre>



<h2> <strong> DEMO ACCOUNTS </strong> </h2>


<strong>NORMAL USERS</strong>

- Username: andreas.magness / crystal.rohrer / daina.vandyne <br>
- Password: customer123<br>


<strong>ADMIN USERS</strong>

- Username: john.doe / jane.smith / simon.campos / meaghan.park <br>
- Password: tutor123<br>


<h2> <strong> DOCUMENTATION </strong> </h2>
<a href="https://classiebit.com/classeventie/docs">Click here for ‘How To Use Guide - Classeventie'</a><br>


<h2>SUPPORT </h2>
You can email us at info@gmail.com for any type of support, issue or query. We’ll get back to you within 24 Hours. <br><br>


<h2> <strong> INSTALLATION SUPPORT POLICY </strong></h2>

- If you need any other custom change then it would be at extra cost (hourly rates).<br>
- If we need to configure server first in order to install the script then server configuration changes will 50$.<br>
- Free installation is only for single domain / server. If you want installation on any other domain or server then
charges would be 50$ / domain.
- If you're getting any error, then just put an email regarding the error, and we'll update the script after error resolution within 2 days.


<h2> <strong> CHANGELOG </strong> </h2>

<h3>v2 June 06, 2018</h3>
<pre>
<code>

=== Version 2 ===

-/-/-/- upgrading from V1 to V2 guide -/-/-/-

- Replace following folders by new version - 
    - system (folder)
    - themes (folder)
    - application(folder)

- Update database by the following two simple queries
  - Go to Table `batches` and run-
    
    ALTER TABLE `batches` ADD `recurring` BOOLEAN NULL DEFAULT FALSE AFTER `weekdays`, ADD `recurring_type` ENUM('first_week','second_week','third_week','fourth_week','every_week') NOT NULL DEFAULT 'every_week' AFTER `recurring`;

  
  - Go to Table `b_bookings` and run-
  
    ALTER TABLE `b_bookings` ADD `batch_recurring` TINYINT(4) NOT NULL AFTER `batch_capacity`, ADD `batch_weekdays` TEXT NULL DEFAULT NULL AFTER `batch_recurring`;

</code>
</pre>


<h3>v1.2.3 Apr 29, 2018</h3>
<pre>
<code>

=== Version 1.2.3 ===

-/-/-/- Bug Resolved -/-/-/-
- Reset password email not working - RESOLVED (file changed - application/libraries/Ion_auth.php) 

</code>
</pre>


<h3>v1.2.2 Mar 09, 2018</h3>
<pre>
<code>

=== Version 1.2.2 ===

-/-/-/- Bug Resolved -/-/-/-
- Changed naming conventions of all Classes (for latest PHP version compatibility)
   Before - B_booking.php | Now - Bbooking.php

</code>
</pre>


<h3>v1.2.1 Feb 13, 2018</h3>
<pre>
<code>

=== Version 1.2.1 ===

-/-/-/- Bug Resolved -/-/-/-
- Updated index.php file (root directory)

</code>
</pre>


<h3>v1.2 Feb 10, 2018</h3>
<pre>
<code>

=== Version 1.2 ===

-/-/-/- Bugs Resolved -/-/-/-
- Forgot Password error - RESOLVED
- Old MySql version compatibility - ADDED
- Language change warnings - REMOVED
- Installer Error - RESOLVED
- Frontend Event & Batches Timing error - RESOLVED

</code>
</pre>

<h3>v1 Jul 19, 2017</h3>
<pre>
<code>

=== Version 1 ===

** Initial release

</code>
</pre>



<h2>THANK YOU </h2>
If you like this product, you may like our other products as well. 
<a href="https://classiebit.com/products">Click here to see all our products</a> 

