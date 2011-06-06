jQuery(document).ready(function()
{

    Administry.setup();
    
    jQuery("#news").newsTicker();

    $('#tabs').tabs();
    
    slideShow();
});

var gid = function(x) { return document.getElementById(x); };
function focus_text(msg, id){
    if(gid(id).value == msg){
        gid(id).value="";
    }
}
function blur_text(msg, id){
    if(gid(id).value.length < 1){
        gid(id).value=msg;
    }
}

function slideShow()
{
    //Set the opacity of all images to 0
    jQuery('#gallery a').css({opacity: 0.0});

    //Get the first image and display it (set it to full opacity)
    jQuery('#gallery a:first').css({opacity: 1.0});

    //Set the caption background to semi-transparent
    jQuery('#gallery .caption').css({opacity: 0.7});

    //Resize the width of the caption according to the image width
    jQuery('#gallery .caption').css({width: jQuery('#gallery a').find('img').css('width')});

    //Get the caption of the first image from REL attribute and display it
    jQuery('#gallery .content').html(jQuery('#gallery a:first').find('img').attr('rel'))
    .animate({opacity: 0.7}, 400);

    //Call the gallery function to run the slideshow, 6000 = change to next image after 6 seconds
    setInterval('gallery()',6000);
}

function gallery()
{
    //if no IMGs have the show class, grab the first image
    var current = (jQuery('#gallery a.show')?  jQuery('#gallery a.show') : jQuery('#gallery a:first'));

    //Get next image, if it reached the end of the slideshow, rotate it back to the first image
    var next = ((current.next().length) ? ((current.next().hasClass('caption'))? jQuery('#gallery a:first') :current.next()) : jQuery('#gallery a:first'));

    //Get next image caption
    var caption = next.find('img').attr('rel');

    //Set the fade in effect for the next image, show class has higher z-index
    next.css({opacity: 0.0})
    .addClass('show')
    .animate({opacity: 1.0}, 1000);

    //Hide the current image
    current.animate({opacity: 0.0}, 1000)
    .removeClass('show');

    //Set the opacity to 0 and height to 1px
    jQuery('#gallery .caption').animate({opacity: 0.0}, { queue:false, duration:0 }).animate({height: '1px'}, { queue:true, duration:300 });

    //Animate the caption, opacity to 0.7 and heigth to 100px, a slide up effect
    jQuery('#gallery .caption').animate({opacity: 0.7},100 ).animate({height: '100px'},500 );

    //Display the content
    jQuery('#gallery .content').html(caption);
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
