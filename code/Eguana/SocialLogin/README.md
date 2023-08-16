# Eguana SocialLogin

`Website` : Main Website URL
`Module Name` : Eguana_SocialLogin



# Kakao Link


First of all we need to configure our kakao environment with

`1: kakao developer`

`2. project`

`3. kakao social login account`

so

**we can use kakao social user account with our project to sign in sign up , can link and unlink.**


# 1: kakao developer


in kakao develpoer we need to have an app and its ***App Keys***

`App Keys as`

    Native app key	 
    REST API key	 
    JavaScript key	 
    Admin key`


we can get from : My Application > App Settings > App Key

`project web domain name registed as `

    site domain
`

we can set at : My Application > plate form > Web


then we need to set our call back url

` Redirect URL for logout` set at My Application > kakao Login > Redirect URL

` Redirect URL` set at My Application > kakao Login > Redirect URL





**Allow consent to provide more diverse user data required for signup.**





1- Kakao Develpoer Login
2- Select Application

My Application -> Product Settings -> Kakao Login ->Consent Items


Set consent items

>> Nickname
>
>> Profile image
>
> >Email
>
> >Name
>
> >Gender
>
> > Age range
>
> > Birthday
>
> >Birth Year
>
> >phone

# 2 project config


go to admin dashboard

`STORE ->  CONFIGURATION  -> EGUANA EXTENSION -> SOCIAL LOGIN `

kAKAO

    ENABLE (enable extension)
    Client Id (get from kakao develpoer account)
    App Id (get from kakao develpoer account)
    OAuth Redirect URI (same you add in kakao develpoer account redirect url which is as https://test.honeynaps.com/kakaologin/callback )


Kakao Logout

    base URL : https://accounts.kakao.com/logout?continue=https://kauth.kakao.com
    Logout Redirect URL : http://test.honeynaps.com/customer/account/logout

kakao Unlink

    base url : https://kapi.kakao.com
    App Admin Key (get from develpoer accouunt)



# SIGN IN SIGN UP WITH KAKAO
click on kakao login icon enter kakao credentials and proceed with data for the first time it ask you to allow conecents.

If kakao social account isnot link with project so for very first time
when you click sigin with kakao button it ask fo consents as
which can be set from : kakao Develpoer -> Login -> Consents items as above we set.

then if customer all data automatically fill to all fields so customer just add password and proceed with account creation is done.
### kakao Login

if customer has account in honyname then he just click on button "login with kakao"
he will go to kakao login page once he/she login kakao social login then he with just go to honynap dashboard by just clicking on button.




remove app as concents and app access

1- login to kako App account
2- goto the user account information
3- check use your account
4- click on view button
5- select any linked app which you want to remove consents and access unlink
5- Delete all data and Disconnect it
5- enter confirmation paswword

it will unlink you account from that site .



### it also automatically unlink when customer will delete from honynap admin pannel single customer delete.



then can be test as  
    1 - auto login if account already exist by just click app
2 - if not then data auto fill to fields 
    3-  just add new password and sign up
4- first time it ask for concents you to allow 
    4- after unlink by app manually or also it unlinked when any customer delete so for next time he will renew all consents at allow in projects
5- if customer logout it also logout website  he also logout from the app 


also it can be check when you again click on sign by kakao button it will again ask for allow access for consents


### if customer logout from honynap it will also logout browser kakao social  login

you can check your kakao social login account from same system also logout.



on sign up with kakao page when we click on
sign kakao icon add social login kakao then sign page of honnynap automatically fill all data of user from kakao and in case any of fields is missing in kakao social login account then we need to add manually fill that field because all are required fields all all fields auto fill to just enter password and you account is done by single click all data will be set accordingly.    


