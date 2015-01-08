------------------------------------------------------------------------------------------------
Problem Statement:
-----------------------------------------------------------------------------------------------

Index: Determine the strength of the keyword on a web page and index the keyword and strength against the url.
Search: When keywords are given as input, show the web sites based on strength in descending order

To solve this problem, use local file storage to maintain the details about key word, strength and web page.

Problem Details:

The solution you provide will have two modes.

1. Index Mode:- Index mode accepts an url as an input, crawls the url, identifies the keywords, determines the count and stores it in the file storage. Please ignore adjectives and conjunctions while considering the key words.
2. Search Mode:- Accepts a word as input, Uses the details stored in the file storage (Index Mode) and shows the urls against keyword strength in descending order.

Inputs to Index Mode

www.helpinglostpets.com
www.jobsite.co.uk
www.stllostpets.org

Inputs to Search Mode
cats
dogs
---------------------------------------------------------------------------------------------
How to run the system:
----------------------------------------------------------------------------------------------

1.Index Mode Sample Input:
   php index_mode.php http://www.helpinglostpets.com/ http://www.jobsite.co.uk/ http://www.stllostpets.org/
 
2.Index Mode Sample Output:
  local_array.txt file is created with the serialzed data of keywords associated with each URL.

3.Search Mode Sample Input:
  php search_mode.php dogs
  
4.Search Mode Sample Output:
  25 resutls found in http://www.helpinglostpets.com/ 
  1 resutls found in http://www.stllostpets.org/ 
  
---------------------------------------------------------------------------------------------------
Note:
---------------------------------------------------------------------------------------------------
1.The solution works in public network only.
2.The index mode inputs must be URLs and they should have http:// prefix.
3.There can be more than one URL given as inpit to the index mode.
4.Only one argument given as a serch keyword works for search mode

  
  


  





