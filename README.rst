=========================================
OWNewsletter for eZ Publish documentation
=========================================

.. image:: https://github.com/Open-Wide/OWNewsletter/raw/master/doc/images/Open-Wide_logo.png
    :align: center

:Extension: OW Newsletter v1.0
:Requires: eZ Publish 4.x.x (not tested on 3.X)
:Author: Open Wide http://www.openwide.fr

Presentation
============

This extension provides a complete system to create and send newsletters.

LICENCE
-------
This eZ Publish extension is provided *as is*, in GPL v2 (see LICENCE).

Installation
============

1. Clone the repository in the extension folder :

.. code-block:: sh

    $ git clone https://github.com/Open-Wide/OWNewsletter.git extension/ownewsletter

2. Enable the extension in the site.ini.append.php :

.. code-block:: php

    ActiveExtensions[]=ownewsletter

3. Update the autoload arrays and clear cache :

.. code-block:: sh

    $ bin/php/ezpgenerateautoloads.php --extension
    $ bin/php/ezcache.php --clear-all

4. Create the following classes using the content package in ``package`` directory or using OWMigration  :

* In the group ``Newsletter``
    * Newsletter
    * Newsletter collection
    * Newsletter mailing list
    * Newsletter mailing list collection
    * Newsletter system
* In the group ``Newsletter Editions``
    * Newsletter edition

5. Create a newsletter section associated with the newsletter navigation part.

6. Enable the cronjobs

Usage
=====

Content tree
------------

Create the following content tree in newsletter section :

* Newsletters [Newsletter collection] 
    * Newsletters [Newsletter system]
        *  Mailing list collection 1 [Newsletter mailing list collection]
            * Mailing list 1 [Newsletter mailing list] 
            * Mailing list 2 [Newsletter mailing list] 
            * Mailing list 3 [Newsletter mailing list] 
        *  Mailing list collection 2 [Newsletter mailing list collection]
            * Mailing list 4 [Newsletter mailing list] 
            * Mailing list 5 [Newsletter mailing list]
        * Newsletter 1 [Newsletter]
            * Edition 1 [Newsletter edition]
            * Edition 2 [Newsletter edition]
            * Edition 3 [Newsletter edition]
        * Newsletter 2 [Newsletter] 
            * Edition 1 [Newsletter edition]
            * Edition 2 [Newsletter edition]
            * Edition 3 [Newsletter edition]
            * Edition 4 [Newsletter edition]

``Newsletter collection`` : must be unique.

``Newsletter system`` : it includes mailing lists and newsletters. Each system is independent of other.

``Newsletter mailing list collection``: a collection includes several mailing lists. For exemple, you can have a collection by site or a collection by language.

``Newsletter mailing list`` : it is the content which is attached to user subscriptions. You can choose from which siteaccess the user can subscribe and if the subscribtion is automatically approved.

``Newsletter`` : it is a set of newsletter editions. You can select the default mailing lists which send editions, the siteaccess used to generate the newsletter, the email address and the name of the sender, the default email addresses of recipients of test sending, the skin of the newsletter and enable some the customisation.

``Newsletter edition`` : it is the mail sending to the subscribers. You can select the mailing lists which send the edition.

The Newsletter edition classes
------------------------------

You can create as many Newsletter edition classes as you want as long as they :

* are in the group ``Newsletter Editions``
* contains a ``Newsletter Edition`` attribute

The template email associated with your class is : newsletter/skin/<skinName>/output/<classIdentifier>.tpl

Mail customisation
------------------

In ``newsletter.ini`` :

.. code-block:: ini

    [NewsletterMailPersonalizations]
    AvailableMailPersonalizations[]=my_customisation

    [my_customisation-MailPersonalizationSettings]
    Name=My customisation
    Class=MyExtentionMyCustomisationMailPersonalization

In the customisation class (``MyExtentionMyCustomisationMailPersonalization``), implement the methods :

.. code-block:: php

    static function applyOnSubject( $subject, $newsletterUser ) {
        // TOTO
    }

    static function applyOnHTMLBody( $HTMLBody, $newsletterUser ) {
        // TOTO
    }

    static function applyOnPlainTextBody( $plainTextBody, $newsletterUser ) {
        // TOTO
    }

For exemple, see the ``OWNewsletterUserInfoMailPersonalization`` class.

Subscribtion form customisation
-------------------------------

In the ``newsletter.ini``, you can add some fields to the subscription form. See the ``[NewsletterUserSettings]`` part for more info.

Other
=====

Icons : http://neurovit.deviantart.com/art/simplicio-92311415
