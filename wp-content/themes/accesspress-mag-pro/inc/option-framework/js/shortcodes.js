(function() {
   tinymce.PluginManager.add('accesspress_pro_mce_button', function( editor, url ) {
      editor.addButton( 'accesspress_pro_mce_button', {
         text: 'Short Codes',
         icon: false,
         type: 'menubutton',
         menu: [
            {
               text: 'Layouts',
               menu: [
                  {
                     text: 'Grid',
                     onclick: function() {
                        editor.windowManager.open( {
                           title: 'Insert no columns to show in a row',
                           id:'column-selector',
                           body: [
                              {
                                 type: 'listbox',
                                 name: 'columns',
                                 label: 'No of Columns',
                                 id :'no-of-columns',
                                 'values': [
                                    {text: '1', value: '1'},
                                    {text: '2', value: '2'},
                                    {text: '3', value: '3'},
                                    {text: '4', value: '4'},
                                    {text: '5', value: '5'},
                                    {text: '6', value: '6'},
                                 ]
                              },
                              {
                                 type: 'listbox',
                                 name: 'first_column',
                                 label: 'First Column Width',
                                 id:'first_column',
                                 'values': [
                                    {text: '1', value: '1'},
                                    {text: '2', value: '2'},
                                    {text: '3', value: '3'},
                                    {text: '4', value: '4'},
                                    {text: '5', value: '5'},
                                    {text: '6', value: '6'},
                                 ]
                              },
                              {
                                 type: 'listbox',
                                 name: 'second_column',
                                 label: 'Second Column Width',
                                 id:'second_column',
                                 'values': [
                                    {text: '1', value: '1'},
                                    {text: '2', value: '2'},
                                    {text: '3', value: '3'},
                                    {text: '4', value: '4'},
                                    {text: '5', value: '5'},
                                    {text: '6', value: '6'},
                                 ]
                              },
                              {
                                 type: 'listbox',
                                 name: 'third_column',
                                 label: 'Third Column Width',
                                 id:'third_column',
                                 'values': [
                                    {text: '1', value: '1'},
                                    {text: '2', value: '2'},
                                    {text: '3', value: '3'},
                                    {text: '4', value: '4'},
                                    {text: '5', value: '5'},
                                    {text: '6', value: '6'},
                                 ]
                              },
                              {
                                 type: 'listbox',
                                 name: 'fourth_column',
                                 label: 'Fourth Column Width',
                                 id:'fourth_column',
                                 'values': [
                                    {text: '1', value: '1'},
                                    {text: '2', value: '2'},
                                    {text: '3', value: '3'},
                                    {text: '4', value: '4'},
                                    {text: '5', value: '5'},
                                    {text: '6', value: '6'},
                                 ]
                              },
                              {
                                 type: 'listbox',
                                 name: 'fifth_column',
                                 label: 'Fifth Column Width',
                                 id:'fifth_column',
                                 'values': [
                                    {text: '1', value: '1'},
                                    {text: '2', value: '2'},
                                    {text: '3', value: '3'},
                                    {text: '4', value: '4'},
                                    {text: '5', value: '5'},
                                    {text: '6', value: '6'},
                                 ]
                              },
                              {
                                 type: 'listbox',
                                 name: 'sixth_column',
                                 label: 'Sixth Column Width',
                                 id:'sixth_column',
                                 'values': [
                                    {text: '1', value: '1'},
                                    {text: '2', value: '2'},
                                    {text: '3', value: '3'},
                                    {text: '4', value: '4'},
                                    {text: '5', value: '5'},
                                    {text: '6', value: '6'},
                                 ]
                              },

                           ],
                           onsubmit: function( e ) {
                              
                                 if(e.data.columns == 1){
                                    editor.insertContent( 
                                   '[ap_column_wrap]<br />'+ 
                                   '[ap_column span="'+e.data.first_column+'"]Put your column 1 text[/ap_column]<br />'+
                                   '[/ap_column_wrap]<br />'
                                    );
                                 }else if(e.data.columns == 2){
                                    if((parseInt(e.data.first_column) + parseInt(e.data.second_column)) < 7 ){
                                    editor.insertContent( 
                                    '[ap_column_wrap]<br />'+ 
                                    '[ap_column span="'+e.data.first_column+'"]Put your column 1 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.second_column+'"]Put your column 2 text[/ap_column]<br />'+
                                    '[/ap_column_wrap]<br />'
                                    );
                                    }else{
                                       alert('Invalid! Sum of columns should exceed 6');
                                 }
                                 }else if(e.data.columns == 3){
                                    if((parseInt(e.data.first_column) + parseInt(e.data.second_column) + parseInt(e.data.third_column)) < 7 ){
                                    editor.insertContent( 
                                    '[ap_column_wrap]<br />'+ 
                                    '[ap_column span="'+e.data.first_column+'"]Put your column 1 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.second_column+'"]Put your column 2 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.third_column+'"]Put your column 3 text[/ap_column]<br />'+
                                    '[/ap_column_wrap]<br />'
                                    );
                                    }else{
                                    alert('Invalid! Sum of columns should exceed 6');
                                 }
                                 }else if(e.data.columns == 4){
                                    if((parseInt(e.data.first_column) + parseInt(e.data.second_column) + parseInt(e.data.third_column) + parseInt(e.data.fourth_column)) < 7 ){
                                     editor.insertContent( 
                                    '[ap_column_wrap]<br />'+ 
                                    '[ap_column span="'+e.data.first_column+'"]Put your column 1 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.second_column+'"]Put your column 2 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.third_column+'"]Put your column 3 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.fourth_column+'"]Put your column 4 text[/ap_column]<br />'+
                                    '[/ap_column_wrap]<br />'
                                    );
                                     }else{
                                    alert('Invalid! Sum of columns should exceed 6');
                                 }
                                 }else if(e.data.columns == 5){
                                    if((parseInt(e.data.first_column) + parseInt(e.data.second_column) + parseInt(e.data.third_column) + parseInt(e.data.fourth_column) + parseInt(e.data.fifth_column)) < 7 ){
                                    editor.insertContent( 
                                    '[ap_column_wrap]<br />'+ 
                                    '[ap_column span="'+e.data.first_column+'"]Put your column 1 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.second_column+'"]Put your column 2 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.third_column+'"]Put your column 3 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.fourth_column+'"]Put your column 4 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.fifth_column+'"]Put your column 5 text[/ap_column]<br />'+
                                    '[/ap_column_wrap]<br />'
                                    );
                                    }else{
                                    alert('Invalid! Sum of columns should exceed 6');
                                 }
                                 }else if(e.data.columns == 6){
                                    if((parseInt(e.data.first_column) + parseInt(e.data.second_column) + parseInt(e.data.third_column) + parseInt(e.data.fourth_column) + parseInt(e.data.fifth_column) + parseInt(e.data.sixth_column)) < 7 ){
                                    editor.insertContent( 
                                    '[ap_column_wrap]<br />'+ 
                                    '[ap_column span="'+e.data.first_column+'"]Put your column 1 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.second_column+'"]Put your column 2 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.third_column+'"]Put your column 3 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.fourth_column+'"]Put your column 4 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.fifth_column+'"]Put your column 5 text[/ap_column]<br />'+
                                    '[ap_column span="'+e.data.sixth_column+'"]Put your column 6 text[/ap_column]<br />'+
                                    '[/ap_column_wrap]<br />'
                                    );
                                    }else{
                                       alert('Invalid! Sum of columns should exceed 6');
                                 }
                                 }
                           }
                        });
                     }
                  },
                  {
                     text: 'Divider',
                     onclick: function() {
                     editor.windowManager.open( {
                     title: 'Divider Settings',
                     body: [
                           {
                              type: 'textbox',
                              name: 'border_color',
                              label: 'Border Color',
                              value: '#CCCCCC'
                           },
                           {
                              type: 'listbox',
                              name: 'border_style',
                              label: 'Border Style',
                              'values': [
                                 {text: 'Solid', value: 'solid'},
                                 {text: 'Dashed', value: 'dashed'},
                                 {text: 'Dotted', value: 'dotted'},
                                 {text: 'Double', value: 'double'}
                              ]
                           },
                           {
                              type: 'textbox',
                              name: 'thickness',
                              label: 'Border Thickness',
                              value: '1px'
                           },
                           {
                              type: 'textbox',
                              name: 'border_width',
                              label: 'Border Width',
                              value: '100%'
                           },
                           {
                              type: 'textbox',
                              name: 'mar_top',
                              label: 'Top Spacing',
                              value: '20px'
                           },
                           {
                              type: 'textbox',
                              name: 'mar_bot',
                              label: 'Bottom Spacing',
                              value: '20px'
                           },
                      
                        ],
                        onsubmit: function( e ) {
                           editor.insertContent('[ap_divider color="'+e.data.border_color+'" style="'+e.data.border_style+'" thickness="'+e.data.thickness+'" width="'+e.data.border_width+'" mar_top="'+e.data.mar_top+'" mar_bot="'+e.data.mar_bot+'"]');
                        }
                       });
                     }
                  },
                  {
                     text: 'Spacing',
                     onclick: function() {
                     editor.windowManager.open( {
                     title: 'Spacing Settings',
                     body: [
                           {
                              type: 'textbox',
                              name: 'spacing_height',
                              label: 'Spacing Height',
                              value: '10px'
                           }
                        ],
                        onsubmit: function( e ) {
                           editor.insertContent('[ap_spacing spacing_height="'+e.data.spacing_height+'"]');
                        }
                       });
                     }
                  }
               ]
            },
            {
               text: 'Elements',
               menu: [
                  {
                     text: 'Testimonial',
                     onclick: function() {
                     editor.windowManager.open( {
                     title: 'Testimonial Block',
                     body: [
                           {
                              type: 'textbox',
                              name: 'client_name',
                              label: 'Client Name',
                              value: ''
                           },
                           {
                              type: 'textbox',
                              name: 'client_designation',
                              label: 'Client Designation',
                              value: ''
                           },
                           {
                              type: 'textbox',
                              name: 'image_url',
                              label: 'Image Url',
                              value: ''
                           },
                           {
                              type: 'textbox',
                              name: 'testimonial_desc',
                              label: 'Testimonial',
                              value: 'Write the Clients Testimonial Here',
                              multiline: true,
                              minWidth: 300,
                              minHeight: 150
                           },
                              
                        ],
                        onsubmit: function( e ) {
                             editor.insertContent('[ap_testimonial image="'+e.data.image_url+'" image_shape="'+e.data.testimonial_image_shape+'" client="'+e.data.client_name+'" designation="'+e.data.client_designation+'"]<br />'+e.data.testimonial_desc+'<br />[/ap_testimonial]'); 
                        }
                       });
                     }
                  },
                  {
                     text: 'Team',
                     onclick: function() {
                     editor.windowManager.open( {
                     title: 'Team Block',
                     body: [
                           {
                              type: 'textbox',
                              name: 'team_member_name',
                              label: 'Team Member Name',
                              value: ''
                           },
                           {
                              type: 'textbox',
                              name: 'team_member_position',
                              label: 'Team Member Designation',
                              value: ''
                           },
                           {
                              type: 'textbox',
                              name: 'team_upload',
                              label: 'Image Url',
                              value: ''
                           },
                           {
                              type: 'textbox',
                              name: 'team_detail',
                              label: 'Detail',
                              value: 'Write the Team Member Detail Here',
                              multiline: true,
                              minWidth: 300,
                              minHeight: 150
                           },
                              
                        ],
                        onsubmit: function( e ) {
                             editor.insertContent('[ap_team image="'+e.data.team_upload+'" name="'+e.data.team_member_name+'" designation="'+e.data.team_member_position+'"]<br />'+e.data.team_detail+'<br />[/ap_team]'); 
                        }
                       });
                     }
                  },
                  {
                     text: 'Toggle',
                     onclick: function() {
                     editor.windowManager.open( {
                     title: 'Toggle',
                     body: [
                           {
                              type: 'textbox',
                              name: 'toggle_heading',
                              label: 'Heading',
                              value: 'Your Heading',
                              minWidth: 400,
                           },
                           {
                              type: 'textbox',
                              name: 'toggle_detail',
                              label: 'Detail',
                              value: 'Write Detail Here',
                              multiline: true,
                              minWidth: 400,
                              minHeight: 150
                           },
                           {
                              type: 'listbox',
                              name: 'open_close',
                              label: 'Open/Close',
                              'values': [
                                 {text: 'Close', value: 'close'},
                                 {text: 'Open', value: 'open'}
                              ]
                           },
                        ],
                        onsubmit: function( e ) {
                             editor.insertContent('[ap_toggle title="'+e.data.toggle_heading+'" status="'+e.data.open_close+'"]'+e.data.toggle_detail+'[/ap_toggle]'); 
                        }
                       });
                     }
                  },
                  {
                     text: 'Call to Action',
                     onclick: function() {
                     editor.windowManager.open( {
                     title: 'Call to Action Setting',
                     body: [
                           {
                              type: 'textbox',
                              name: 'call_to_action',
                              label: 'Call to Action Text',
                              value: 'Call to action text',
                              multiline: true,
                              minWidth: 500,
                              minHeight: 150
                           },
                           {
                              type: 'textbox',
                              name: 'call_to_action_btn',
                              label: 'Button Text',
                              value: 'Read More',
                              minWidth: 500,
                           },
                           {
                              type: 'textbox',
                              name: 'call_to_action_btn_url',
                              label: 'Button Url',
                              value: '#',
                              minWidth: 500,
                           },
                           {
                              type: 'listbox',
                              name: 'btn_align',
                              label: 'Button Align',
                              'values': [
                                 {text: 'Center', value: 'center'},
                                 {text: 'Right', value: 'right'}
                              ]
                           },
                        ],
                        onsubmit: function( e ) {
                             editor.insertContent('[ap_call_to_action button_text="'+e.data.call_to_action_btn+'" button_url="'+e.data.call_to_action_btn_url+'" button_align="'+e.data.btn_align+'"]'+e.data.call_to_action+'[/ap_call_to_action]'); 
                        }
                       });
                     }
                  },
                  {
                     text: 'Tagline Box',
                     onclick: function() {
                     editor.windowManager.open( {
                     title: 'Tagline Box Setting',
                     body: [
                           {
                              type: 'textbox',
                              name: 'ap_tagline_text',
                              label: 'Tagline Text',
                              value: 'Enter you Tag Line text here',
                              multiline: true,
                              minWidth: 500,
                              minHeight: 150
                           },
                           {
                              type: 'listbox',
                              name: 'tag_box_style',
                              label: 'Tag Box Style',
                              'values': [
                                 {text: 'Border Box', value: 'ap-all-border-box'},
                                 {text: 'Top Border Box', value: 'ap-top-border-box'},
                                 {text: 'Left Border Box', value: 'ap-left-border-box'},
                                 {text: 'Theme Background Box', value: 'ap-bg-box'}
                              ]
                           }
                        ],
                        onsubmit: function( e ) {
                             editor.insertContent('[ap_tagline_box tag_box_style="'+e.data.tag_box_style+'"]'+e.data.ap_tagline_text+'[/ap_tagline_box]'); 
                        }
                       });
                     }
                  },
                  {
                     text: 'Slider',
                     onclick: function() {
                     editor.windowManager.open( {
                     title: 'Slider Settings',
                     body: [
                           {
                              type: 'textbox',
                              name: 'no_of_img',
                              label: 'No of Image',
                              value: '4',
                              minWidth: 500,
                           },
                           {
                              type: 'listbox',
                              name: 'show_caption',
                              label: 'Show Caption',
                              'values': [
                                 {text: 'Yes', value: 'yes'},
                                 {text: 'No', value: 'no'}
                              ]
                           },
                           {
                              type: 'listbox',
                              name: 'link_image',
                              label: 'Link Image to Url',
                              'values': [
                                 {text: 'Yes', value: 'yes'},
                                 {text: 'No', value: 'no'}
                              ]
                           },
                           {
                              type: 'listbox',
                              name: 'open_link',
                              label: 'Open Link',
                              'values': [
                                 {text: 'In Same Tab', value: 'self'},
                                 {text: 'In Different Tab', value: 'blank'}
                              ]
                           },
                        ],
                        onsubmit: function( e ) {
                           var caption, link_image, open_link, j;
                           
                           editor.insertContent('[ap_slider]');
                           for(i=1; i <= e.data.no_of_img; i++){
                              caption = e.data.show_caption=="yes" ? 'caption="Caption text'+i+'"' : '';
                              link_image = e.data.link_image=="yes" ? 'link="http://linkto'+i+'"' : '';
                              open_link = e.data.open_link=="self" ? 'target="_self"' : 'target="_blank"';

                              editor.insertContent(
                              '<br />[ap_slide '+caption+' '+link_image+' '+open_link+']http://your_image_url'+i+'[/ap_slide]'
                              ); 
                             }
                           editor.insertContent('<br />[/ap_slider]');
                        }
                       });
                     }
                  },
                  {
                     text: 'Tab',
                     onclick: function() {
                     editor.windowManager.open( {
                     title: 'Tab Settings',
                     body: [
                           {
                              type: 'textbox',
                              name: 'no_of_tab',
                              label: 'No of Tabs',
                              value: '4',
                              minWidth: 300,
                           },
                           {
                              type: 'listbox',
                              name: 'tab_type',
                              label: 'Show Caption',
                              'values': [
                                 {text: 'Horizontal', value: 'horizontal'},
                                 {text: 'Vertical', value: 'vertical'}
                              ]
                           },
                        ],
                        onsubmit: function( e ) {
                           var j;
                           
                           editor.insertContent('[ap_tab_group type="'+e.data.tab_type+'"]');
                           for(j=1; j <= e.data.no_of_tab; j++){
                              editor.insertContent(
                              '<br />[ap_tab title="Title '+j+'"]Content '+j+'[/ap_tab]'
                              ); 
                             }
                           editor.insertContent('<br />[/ap_tab_group]');
                        }
                       });
                     }
                  },
                  {
                     text: 'List Style',
                     onclick: function() {
                     editor.windowManager.open( {
                     title: 'Select List style',
                     body: [
                           {
                              type: 'textbox',
                              name: 'no_of_list',
                              label: 'No of List Items',
                              value: '4',
                              minWidth: 300,
                           },
                           {
                              type: 'listbox',
                              name: 'list_type',
                              label: 'List Icon',
                              'values': [
                                 {text: 'Thunder Icon', value: 'ap-list1'},
                                 {text: 'Pin Icon', value: 'ap-list2'},
                                 {text: 'Tick Icon', value: 'ap-list3'},
                                 {text: 'Star Icon', value: 'ap-list4'},
                                 {text: 'Money Bag Icon', value: 'ap-list5'},
                                 {text: 'Square Icon', value: 'ap-list6'}
                              ]
                           },
                        ],
                        onsubmit: function( e ) {
                           var k;
                           
                           editor.insertContent('[ap_list list_type="'+e.data.list_type+'"]');
                           for(k=1; k <= e.data.no_of_list; k++){
                              editor.insertContent(
                              '<br />[ap_li]List Item '+k+'[/ap_li]'
                              ); 
                             }
                           editor.insertContent('<br />[/ap_list]');
                        }
                       });
                     }
                  },
                  {
                     text: 'Button',
                     onclick: function() {
                     editor.windowManager.open( {
                     title: 'Button Setting',
                     body: [
                           {
                              type: 'textbox',
                              name: 'button_url',
                              label: 'Buttom Url',
                              value: 'http://',
                              minWidth: 300,
                           },
                           {
                              type: 'textbox',
                              name: 'button_text',
                              label: 'Buttom Text',
                              value: 'Read More',
                              minWidth: 300,
                           },
                           {
                              type: 'listbox',
                              name: 'button_size',
                              label: 'Button Size',
                              'values': [
                                 {text: 'Small', value: 'ap-small-bttn'},
                                 {text: 'Medium', value: 'ap-medium-bttn'},
                                 {text: 'Large', value: 'ap-large-bttn'}
                              ]
                           },
                           {
                              type: 'listbox',
                              name: 'button_type',
                              label: 'Button Type',
                              'values': [
                                 {text: 'Outline Button', value: 'ap-outline-bttn'},
                                 {text: 'Background Button', value: 'ap-bg-bttn'}
                              ]
                           },
                           {
                              type: 'listbox',
                              name: 'button_color',
                              label: 'Button Color',
                              'values': [
                                 {text: 'Default Theme Color', value: 'ap-default-bttn'},
                                 {text: 'Black', value: 'ap-black-bttn'},
                                 {text: 'White', value: 'ap-white-bttn'}
                              ]
                           },
                           {
                              type: 'listbox',
                              name: 'button_align',
                              label: 'Button Align',
                              'values': [
                                 {text: 'None', value: 'ap-align-none'},
                                 {text: 'Left', value: 'ap-align-left'},
                                 {text: 'Right', value: 'ap-align-right'}
                              ]
                           },
                        ],
                        onsubmit: function( e ) {
                           editor.insertContent('[ap_button button_size="'+e.data.button_size+'" button_url="'+e.data.button_url+'" button_type="'+e.data.button_type+'" button_color="'+e.data.button_color+'" button_align="'+e.data.button_align+'"]'+e.data.button_text+'[/ap_button]'); 
                        }
                       });
                     }
                  },
                  {
                     text: 'Drop Caps',
                     onclick: function() {
                     editor.windowManager.open( {
                     title: 'Drop Caps Setting',
                     body: [
                           {
                              type: 'textbox',
                              name: 'letter',
                              label: 'Letter',
                              value: '',
                              minWidth: 300,
                           },
                           {
                              type: 'listbox',
                              name: 'style',
                              label: 'Drop Cap Style',
                              'values': [
                                 {text: 'Normal', value: 'ap-normal'},
                                 {text: 'Square', value: 'ap-square'}
                              ]
                           }
                        ],
                        onsubmit: function( e ) {
                           editor.insertContent('[ap_dropcaps style="'+e.data.style+'"]'+e.data.letter+'[/ap_dropcaps]'); 
                        }
                       });
                     }
                  }
               ]
            }
         ]
      });
   });
})();