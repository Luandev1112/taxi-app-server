# IOS Setup

---

- [Introduction](#section-1)
- [Setup Instructions](#section-2)

<a name="section-1"></a>
## Introduction
In this article, we are going to set up the TYT App’s initial setup for real-time use cases. 

<a name="section-2"></a>
## Setup Instructions

* Open your project File with the Xcode IDE which is used to create the project and also it is very powerful.

Requirements
-------------
1. Xcode 12.3 or less
2. Ios version should be 13 or less if you are using simulator
    
Major things to do:
-------------------

1. Rename the project with your app name by following the below link.
    reference link : https://stackoverflow.com/questions/33370175/how-do-i-completely-rename-an-xcode-project-i-e-inclusive-of-folders

2. Create a bundle identifier for your app in developer account.

3. create development and distribution certificates.

4. Create provisioning profiles for both development and diatribution.

5. Create APNS auth key for push notification.

6. Register your bundle identifier in firebase project.

7. Firebase -: setup phone number authentication, cloud messaging and firebase database.

8. Download the googleservicePlist json from firebase.

9. Now open the renamed project and change the bundle identifier ( setup signin capabalities).

10. Replace Googleservicelist json.

11. Change App Icon in assets.

12. Change launch image in launchscreen.storyboard.

13. Change theme color in UIColor extension (class name UiKitExtension).

14. Change the base url & mqtt url in APIHelper class file.



