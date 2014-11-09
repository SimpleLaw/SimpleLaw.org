function submitCategories(){
    var categories = document.forms["category_checklist"]["category"];
    var url = "laws.php?categories=";
    var i;
    for (i = 0; i < categories.length; i++) {
         if (categories[i].checked) {
               url = url + categories[i].value+",";
         }
     }
   window.location = url.substring(0,url.length-1);
}