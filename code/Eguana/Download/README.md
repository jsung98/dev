# Eguana Download

`Website` : Main Website URL
`Author` : Jason  
`DB Table Name` : eguana_download  
`Listing Page`  : Download page will be "{Main Website URL}/download/index/index" once module is activated'

####Description:

Save customer information, subscribe to the newsletter, and run the DOWNLOAD BROCHURE download from the DOWNLOAD BROCHURE page.

####Key features:

- Admin can Enable or Disable Download, To either show Download page on front end or not to show. (Store => Configuration => Eguana Extensions => Download Brochure => Download Options => Enable Download)
- Admin can add Download information. (Store => Configuration => Eguana Extensions => Download Brochure => Download Options => File Upload)
- Admin can view the downloaded customers in the Grid. (Eguana Extensions => Download)
- Customer can subscribe by selecting the Agree Newsletter check box on the download page.

#Module Installation
```
1.  php bin/magento Module:enable Eguana_Download
2.  php bin/magento setup:upgrade  
3.  php bin/magento setup:di:compile

```

# Test Cases
- That module features are shown at frontend only that time when its is enable from configuration.
- Download content can be set differently for each store view.
