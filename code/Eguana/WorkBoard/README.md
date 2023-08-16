# Eguana Work Board

`Website` : Main Website URL
`DB Table Name` : eguana_board 
`Listing Page`  : Board listing page will be "{Main Website URL}/board" once module is activated'

####Description:

Eguna Work Board allows you to manage the date, content and more information about the Board.

####Key features:

- Admin can Enable or Disable Work Board, To either show Notice on front end or not to show.
- Admin can add Board Title, Board SubTitle, Board Thumbnail,Board category and Description.
- Admin can design the Description content using page builder.
- Admin can select store at which Admin wants to show Board Information.
- All the Board Title, Date and thumbnail images added by admin can view on the frontend listing page.
- After click on Title or thumbnail of Board list detail page will open of that Board and shows Board Title and the content which designed by Admin in Admin Panel using Page Builder.
- Slider is also show at the end of detail page which show thumbnail of of all Board

#Module Installation  
```
1.  php bin/magento Module:enable Eguana_WorkBoard
2.  php bin/magento setup:upgrade  
3.  php bin/magento setup:di:compile

Refresh the Cache under System­ ⇾ Cache Management
```
 **General Configurations**
  
Navigate to **Stores­ ⇾ Configuration**

![store-config](https://nimbus-screenshots.s3.amazonaws.com/s/b7d0f7098eb8912cea0507737a970139.png)

Navigate to EGUANA EXTENSION ⇾ Event Manager in the left panel.

![event-manager-config](https://nimbus-screenshots.s3.amazonaws.com/s/ab4179d50d573ef0a9995ef099f8fb01.png)

Add configuration Values in the following fields and click the save button.

![config-fields](https://nimbus-screenshots.s3.amazonaws.com/s/6404c9595de1a7c2b896dfd68b35e84e.png)

#####(1) Enable or Disable module

Admin can enable and disable module from Enable Feature.

#####(2) Create Category.

Admin can create and delete categories of Board from categories field admin can create categories on store base.

#####(3) No. of Board to load

 Add a number in this field. This number will decide how many Board will show on one page.

#####(4) Board sort type	

 Select one option for Board sort type from Ascending and Descending order options.

#####(3) Now Click on Save Config button to save the configuration.

#  Manage Work Board
Go to Eguana Extensions > Work Board click on Board

It will open a Manage Eguana Extensions Grid. In the grid all records will be shown which admin add by admin panel.

Example image of an admin grid is below.

 **Explore The Grid**
 
## 1) Add New Board

Click on Add New Board Button.

It will open a form to Add New Board.

Below is the example image of the add new form.

![add-nw-form](https://nimbus-screenshots.s3.amazonaws.com/s/a717f2a6cb59c64b7534797e5fd6ef98.png)
![add-nw-form](https://nimbus-screenshots.s3.amazonaws.com/s/9b58c105742fd7f2d269e7d1936c0cca.png)

Explore the every field of add new form in details.

![three-fields](https://nimbus-screenshots.s3.amazonaws.com/s/6582ed0c16a53f16c411e43d415c6593.png)

#####(1) Enable Board

This is Board main enable/disable button. This will decide either Notice is enable to show on front end or if disabled it will not show on the front end.

#####(2) Enable Main Board

This is show main page enable/disable button.

#####(3) Board Title

This is Board main title. Add Board name or title here.

#####(4) Board SubTitle

This is Board subtitle. 

#####(3) Thumbnail Image
 
This field is used to add the thumbnail image which will show on listing page. Click on Upload and select an image which will show in the listing page.


#####(5) Store View

![store-view](https://nimbus-screenshots.s3.amazonaws.com/s/6b92902afb24cabffdc206675b738b19.png)
    
This is Board store view. This will decide where Board information will shown, on multiple stores or on one store.
    
    - If you have only one store, choose Store View.
    - If you want show this Board on multi store,  
    press ctl button and click the stores you want select.
    
#####(6) Category

![store-view](https://nimbus-screenshots.s3.amazonaws.com/s/06c4aeb9aa3654107624b2a48e02b591.png)

Click on to select the category and select categories on the base of selected stores.
    
#####(7) Description

![description](https://nimbus-screenshots.s3.amazonaws.com/s/0166a62bbfbb06b74092e01e77b596bb.png)

This is Board description. In this section drag and drop multiple elements from left panel like images, text, heading, blocks or dynamic blocks etc and create an designed content for description.

#####(8) SEO

![description](https://nimbus-screenshots.s3.amazonaws.com/s/60a7bfafadb5430e984abba87386292c.png)

#####(1) URL Key

Add URL identifer or leave it black it will create the identifer according to the Board Title

#####(2) Meta Title

Add Meta Title

#####(3) Meta Keywords

Add Meta Keywords

#####(4) Meta Description

Add Meta Description

##### Diffrent Save Buttons

![save](https://nimbus-screenshots.s3.amazonaws.com/s/a7d0a88ad0ae9ba64aabc261dfb2d2ff.png)

At the end click on save button to save the Board information.

That new Board does not show on frontend listing page without flush the full page cahce.
so after creating the new Board you need to flush full page cache. 

There are three different buttons to save the Board information

#####(8) Save

Click Save button to save Board information.

#####(9) Save & Duplicate

Click Save & Duplicate option to save Board information and create a copy of current Board we can change some information and add another Board.

#####(9) Save & Close

Click Save & Close option to save Board information and then close the add new form and it will redirect to admin grid page. 

#####(9) Back

and Back button is used to go back on Work Board Manage Grid and it will not save the Board information.

## 2) Search by keyword

![search](https://nimbus-screenshots.s3.amazonaws.com/s/fefe056b2884049dee0af46ea8e87693.png)

Search by keyword is used to search specific keyword word available list of record. Just write the keyword in input field and press the enter button. 

## 3) Filters

![filters](https://nimbus-screenshots.s3.amazonaws.com/s/ab3b561a26d8b97265bf4868222dda6a.png)

Filter option is used to search data but in this you can select range of different options. as you can add ID from 5 to 10, it will show data between 5 and 10 IDs. Similarly you can filter data between two created at dates. or two modified dates. Also you can filter data according to the store scope and Board status.
Add the parameters and click Apply Filters Button

## Action Column

For Board delete, edit and view, go in the last column of Grid **Action** click the **select** Arrow then show two options edit and delete. Where notice can be edit or delete or view.

![action](https://nimbus-screenshots.s3.amazonaws.com/s/5ef1cccbace6d90c4fcc9926141b0a73.png)

## 1) Edit 

Edit Action is used to edit the the existing record by clicking edit it opens the form with current data where we can add changes and update the data.

## 2) Delete

Delete Action is used to delete the selected record.



