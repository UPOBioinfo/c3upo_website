# c3upo
C3UPO cluster website
Web application created to manage the cluster's users.
This web app has 2 sections:

## Registration Section.
It is the main view of the app. If we access to http://www.pvcbacteria.org/c3upo/web/ we have 3 tabs:
### Cluster Information 
Here we can find general information about the cluster structure.
### How to use the Cluster 
In this section are defined some steps about how to use the cluster in a correct way.
### Registration 
Section where the users can register to request cluster access. Once the user completes de registration form a new entry will be created in the administration section so the admin can check the user and create a cluster user for him.
The user can select an existing centre / department / group or can create a new one if it doesn't exist (really it doesn't create a new one, is the admin who decide if create it or not).

## Administration section
It is the section where the admin can manage the users, groups, departments, etc. http://www.pvcbacteria.org/c3upo/web/admin
This section contains the following tabs:
### Users 
Section where the admin can check the users that have already registered in the system and can work with them. In this section the admin can do the following actions:
#### All users
Here we can see all users registered in the system. We can see here those users that have been accepted and an cluster user has been created, those that have been deleted, etc. It contains a legend with colors to have an overview of the status of the diferent users.
#### Users registered in cluster
In this view we can see those users that has been accepted as cluster user and has been included in the cluster. 
Here we have available the action *Edit*, so we can select an user and edit him. So, if the information of a user change it can be updated in this page. Once the information is updated the application will display a popup with the commands that need to be executed in the cluster to update the information of that user.
This view also allow visualize the following information:
##### Active cluster users
In this tab we can see those users that are active in the cluster, that means those users that did the registration in the website and we have accepted them and create an user in the cluster for them.
Here the edit button is also available so we can select an user and modify it. Once modified the app will display a popoup with the commands that need to be run in the cluster to update the data of the user.
Also is available the option *Add to distribution list*. With this functionality we can take control of which users are in the distribution list so they receive the notifications that are send to cluster's users. We have to select an user and click on the button add to distribution list, the system will display a popup with the steps to add the user to the distribution list.
It also possible disable a cluster user. We have to select an user and select the option *Block user*. The user will be mark as blocked and the system will display the command needed to execute in the cluster to block the user.
##### Blocked cluster users
Here we can see those users that are blocked in the cluster. It means that are users that exist in the cluster but whose login is blocked. 
In this section we have the possibility of remove an user from the distribution list. For that the user has to be selected and we have to press the button *Remove from distribution list*. Once it is done the system will mark the user as 'doesn't belong to the distribution list' and a popup with the steps to follow to remove the user from the distribution list will be displayed.
Also is possible activate an user, so the user will be able to login again the cluster. For that we have to select the user and click on *Activate user*, it will mark the user as active and will display a popup with the commands to execute in the cluster to enable him.
#### Pending users
Here we can see those users that did the registration process but hasn't been managed yet.
In this view we can verify the information of those users that are pending throw the option *Verify user*. A popup with the information that the user insert in the register form will be displayed and if the **top admin** agree with give the user access to the cluster we can accept him. We have to be careful with the information that the user has used in the registration due to he has the possibility to create a new centre / department / group. It is also important assign him an username, usually it will be the first letter of his name together with the 3 first letter of his first lastname and the 3 first letters of the second lastname. Once is accepted the user a popup with the commands to execute in the cluster will be displayed.
### Centres
We have the possibility in this view to manage the centres availables for the registration form. All those centres that appear in the table will be displayed in the registration form.
We have the option of create *New centre*, this functionality allow create new centres to be used in the form, that means that will appear in the dropdown menu of centres in the registration form.
Also is possible edit a centre with the option *Edit centre*. With this function we can modify the information of an existing centre.
We can delete a centre with the button *Delete centre*. Those centres that have been already chosen by some user can't be deleted.
And with the option *Users related with the centre* we can see all the users that are registered for a specific centre.
### Departments
In this view we can manage the departments availables for the registration form. All the departments that we have in this view will appear in the dropdown menu for the departments in the registration.
Here we can create new departments with the button *New department*. Creating new departments we add new departments to the list of departments availables in the registration form.
It is also possible edit the information of a department using the button *Edit department*. With that option we can modify the information of an existing department.
We can delete a department using the function *Delete department*. Deleting a department will remove it from the registration form. It is important remember that if a department is related with some user we can't delete it.
And it is also possbile see all the users registered for a specific department using the button *Users related with the department*.
### Group Leaders
Here we have all the existing group leaders. So we have all those persons that are group leader. Those people will appear together with the name of the group in the group dropdown menu in the registration form.
We can craete new group leaders in this section using the button *New group leader*. It will be available in the section Groups when we want to create a new group for the registration form.
It is also possible edit a group leader using the button *Edit group leader*.
Here is possible also delete a group leader, so in case we have inserted someone by mistake it can be deleted using the option *Delete group leader*. If a group leader is used in some group it can't be deleted.
### Groups
This section is to manage the existing groups in the system. Here we have the groups that are availables in the registration form.
We have the option of create a new group using the function *New group*. Here we will need to select an existing group leader. The new group will be displayed in the registration form.
Here we can also edit an existing group using the button *Edit group*. So, we can select a group and edit its content.
It is also possible delete a group throw the action *Delete group*. If a group is deleted it won't appear anymore in the registration form. In case the group is being used by some user it can't be deleted.
And we can see also the users registered for a specific group. So, using the function *Users related with the group* we can see all those users registered in the selected group.
