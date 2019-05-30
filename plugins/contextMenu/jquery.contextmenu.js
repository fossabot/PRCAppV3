/**
 * jQuery plugin for Pretty looking right click context menu.
 *
 * Requires popup.js and popup.css to be included in your page. And jQuery, obviously.
 *
 * Usage:
 *
 *   $('.something').contextPopup({
 *     title: 'Some title',
 *     items: [
 *       {label:'My Item', icon:'/some/icon1.png', action:function() { alert('hi'); }},
 *       {label:'Item #2', icon:'/some/icon2.png', action:function() { alert('yo'); }},
 *       null, // divider
 *       {label:'Blahhhh', icon:'/some/icon3.png', action:function() { alert('bye'); }, isEnabled: function() { return false; }},
 *     ]
 *   });
 *
 * Icon needs to be 16x16. I recommend the Fugue icon set from: http://p.yusukekamiyamane.com/ 
 *
 * - Joe Walnes, 2011 http://joewalnes.com/
 *   https://github.com/joewalnes/jquery-simple-context-menu
 *
 * MIT License: https://github.com/joewalnes/jquery-simple-context-menu/blob/master/LICENSE.txt
 */
jQuery.fn.contextPopup = function (menuData) {
// Define default settings
   var settings = {
      contextMenuClass: 'contextMenuPlugin',
      gutterLineClass: 'gutterLine',
      headerClass: 'header',
      seperatorClass: 'divider',
      title: '',
      items: [],
      rightOrLeft: 'right',
      disabled: false, 
      preopen: function (selector, sett) {
         return sett;
      },
      preopenbyitem: function (selector, item) {
         return item;
      }
   };
   // merge them
   $.extend(settings, menuData);
   // Build popup menu HTML
   function createMenu(e) {
      // chamo a funcao       

      // chamo o pre open general:
      settings = settings.preopen(settings.selector, settings);

      

      // preopen by item:
// abro o preopen by item:
      $.each (settings.items, function(index, value) {
         if (value != null) {
            value = settings.preopenbyitem (settings.selector, value, settings);
         }
         settings.items[index] = value;

      });


      var menu = $('<ul class="' + settings.contextMenuClass + '"><div class="' + settings.gutterLineClass + '"></div></ul>')
              .appendTo(document.body);
      if (settings.title) {
         $('<li class="' + settings.headerClass + '"></li>').text(settings.title).appendTo(menu);
      }
      settings.items.forEach(function (item) {
         if (item) {
            var rowCode = '<li><a href="#"><span></span></a></li>';
            // if(item.icon)
            //   rowCode += '<img>';
            // rowCode +=  '<span></span></a></li>';
            var row = $(rowCode).appendTo(menu);
            if (item.icon) {
               var icon = $('<img>');
               icon.attr('src', item.icon);
               icon.insertBefore(row.find('span'));
            }

            if (item.iconfont) {
               var iconfont = $('<i>');
               iconfont.addClass(item.iconfont);
               iconfont.addClass('contextMenuPluginIconFonts');
               iconfont.insertBefore(row.find('span'));
            }

            if (item.checked != undefined) {
               if (item.checked) {
                  var iconfont = $('<i>');
                  iconfont.addClass("fa fa-check");
                  iconfont.addClass('contextMenuPluginIconFonts');
                  iconfont.insertBefore(row.find('span'));
               }
            }


            row.find('span').text(item.label);
            if ((item.isEnabled != undefined && !item.isEnabled() ) || settings.disabled ) {
               row.addClass('disabled');
            } else if (item.action) {
               row.find('a').click(function () {
                  item.action(e, settings);
               });
            }

         } else {
            $('<li class="' + settings.seperatorClass + '"></li>').appendTo(menu);
         }
      });
      menu.find('.' + settings.headerClass).text(settings.title);
      return menu;
   }

   // On contextmenu event (right click)
   var bindData;
   if (settings.rightOrLeft == 'right') {
      bindData = 'contextmenu';
   } else {
      bindData = 'click';
   }
   settings.selector = this.attr('id');
   
   this.bind(bindData, function (e) {
      var menugen = createMenu(e);
      if (!menugen) {
         return;
      } 
      var menu = menugen.show();
      var left = e.pageX + 5, /* nudge to the right, so the pointer is covering the title */
              top = e.pageY;
      if (top + menu.height() >= $(window).height()) {
         top -= menu.height();
      }
      if (left + menu.width() >= $(window).width()) {
         left -= menu.width();
      }

      // Create and show menu
      menu.css({zIndex: 999999999, left: left, top: top})
              .bind('contextmenu', function () {
                 return false;
              });
              

      // Cover rest of page with invisible div that when clicked will cancel the popup.
      var bg = $('<div></div>')
              .css({left: 0, top: 0, width: '100%', height: '100%', position: 'absolute', zIndex: 999999998})
              .appendTo(document.body)
              .bind('contextmenu click', function () {
                 // If click or right click anywhere else on page: remove clean up.
                 bg.remove();
                 menu.remove();
                 return false;
              });
      // When clicking on a link in menu: clean up (in addition to handlers on link already)
      menu.find('a').click(function () {
         bg.remove();
         menu.remove();
      });

      // Cancel event, so real browser popup doesn't appear.
      return false;
   });
   return this;
};

