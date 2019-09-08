///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// The scripts below will be included in every page, as they are required by every single page of the website

// Stop showing the side menu suggestion when the user starts scrolling
window.addEventListener('scroll', function()
{
  // Only do this if the side menu is hidden
  if(window.getComputedStyle(document.getElementById('header_sidemenu')).display == 'none')
  {
    // Detect where the scroll bar is at
    var currentscroll = Math.max(document.body.scrollTop,document.documentElement.scrollTop);

    // If on top of the page, show the menu again
    if(!currentscroll)
      document.getElementById('header_nomenu').style.display = 'inline';
    else
      document.getElementById('header_nomenu').style.display = 'none';
  }
}, true);