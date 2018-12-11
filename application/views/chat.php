<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<html>
   <head>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet"
   </head>
   <body>
      <?php

        $user_info = $this->session->userdata('user');

        $user_id_ls   = $user_info->id??'';
        $user_name_ls = $user_info->name??'';   
        $token = $user_info->token??'';   

      ?>
      <input type="hidden" id="user_id_ls" name="user_id_ls" value="<?=$user_id_ls;?>">
      <input type="hidden" id="user_name_ls" name="user_name_ls" value="<?=$user_name_ls;?>">
      <input type="hidden" id="token" name="token" value="<?=$token;?>">
      <div class="container">
         <h3 class=" text-center">Live chat &nbsp;<a style="font-size: 11px;" href="<?=base_url('live_chat/logout');?>">logout</a> &nbsp;<i style="font-size: 11px;" class="connection">Trying to connect</i></h3>
         <div class="messaging">
            <div class="inbox_msg">
               <div class="inbox_people">
                  <div class="headind_srch">
                     <div class="recent_heading">
                        <h4>Recent</h4>
                     </div>
                     <div class="srch_bar">
                        <div class="stylish-input-group">
                           <input type="text" class="search-bar"  placeholder="Search" >
                           <span class="input-group-addon">
                           <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                           </span> 
                        </div>
                     </div>
                  </div>
                  <div class="inbox_chat">
                     <div class="chat_list active_chat">
                      <?php 
                        if(!empty($user_list)):
                          foreach ($user_list as $key => $value):
                            if($value->id != $user_id_ls):
                      ?>
                        <div class="chat_people">
                           <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                           <div class="chat_ib">
                              <h5><?=$value->name??'';?><span class="chat_date"><?php echo date('d-m-Y h:i a',strtotime($value->active_on??''));?></span></h5>
                              <p class="activity_status">active</p>
                           </div>
                        </div>
                        <hr>
                      <?php 
                            endif;
                          endforeach;
                        endif;
                      ?>
                     </div>
                  </div>
               </div>
               <div class="mesgs">
                  <div class="msg_history">
                    <?php 

                      if(!empty($messages)):
                        foreach ($messages as $key => $value):
                          if($value->id != $user_id_ls){
                    ?>
                     <div class="incoming_msg">
                        <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt=""><i style="font-size: 10px"> <?=$value->name??'';?> </i></div>
                        <div class="received_msg">
                           <div class="received_withd_msg">
                              <p><?=$value->text??'';?></p>
                              <span class="time_date" style="float: right;"><?= date('d M h:i a',strtotime($value->added_on??'')); ?></span>
                           </div>
                        </div>
                     </div>
                    <?php }else{ ?>
                     <div class="outgoing_msg">
                        <div class="sent_msg">
                           <p><?=$value->text??'';?></p>
                           <span class="time_date"><?= date('d M h:i a',strtotime($value->added_on??'')); ?></span> 
                        </div>
                     </div>
                     <?php
                          }
                        endforeach;
                      endif;
                     ?>
                  </div>
                  <div class="type_msg">
                     <div class="input_msg_write">
                        <input type="text" class="write_msg" placeholder="Type a message" />
                        <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                     </div>
                  </div>
               </div>
            </div>
            <p class="text-center top_spac"> Design by <a target="_blank" href="#">Subash Diego</a></p>
         </div>
      </div>
   </body>
   <script type="text/javascript">
    $(document).ready(function(){

        var conn = new WebSocket('ws://192.168.0.73:12000');
        conn.onopen = function(e) {
            $('.connection').text('connected');
        };

        conn.onmessage = function(e) {

          var pulse = JSON.parse(e.data);

          console.log(pulse.id);

          var id  = typeof(pulse.id) != "undefined" && pulse.id !== null?pulse.id:'';
          var name = typeof(pulse.name) != "undefined" && pulse.name !== null?pulse.name:'';
          var text = typeof(pulse.text) != "undefined" && pulse.text !== null?pulse.text:'';
          var added_on = typeof(pulse.added_on) != "undefined" && pulse.added_on !== null?pulse.added_on:'';
          
          if(id){

              var mms = '<div class="incoming_msg">'+
                        '<div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt=""><i style="font-size: 10px">'+name+'</i></div>'+
                        '<div class="received_msg">'+
                           '<div class="received_withd_msg">'+
                              '<p>'+text+'</p>'+
                              '<span class="time_date" style="float: right;">'+added_on+'</span>'+
                           '</div>'+
                        '</div>'+
                     '</div>';

              $('.msg_history').append(mms);
          }
        
        };

          $('.msg_send_btn').click(function(){
            var value = $('.write_msg').val();
            var token = $('#token').val();

            var data  = {
                          user_id : $('#user_id_ls').val(),
                          message : value,
                          token   : token
                        };

            if(value!=''){

              var today = new Date();

              var mss = '<div class="outgoing_msg">'+
                          '<div class="sent_msg">'+
                             '<p>'+value+'</p>'+
                             '<span class="time_date">'+today.getHours() + ':' + today.getMinutes()+'</span>'+ 
                          '</div>'+
                        '</div>';

              $('.msg_history').append(mss);

              $('.msg_history').scrollTop($('.msg_history')[0].scrollHeight);

              conn.send(JSON.stringify(data));
              $('.write_msg').val('');
            }
          });
        });
   </script>
   <style type="text/css">
      .container{max-width:1170px; margin:auto;}
      img{ max-width:100%;}
      .inbox_people {
      background: #f8f8f8 none repeat scroll 0 0;
      float: left;
      overflow: hidden;
      width: 40%; border-right:1px solid #c4c4c4;
      }
      .inbox_msg {
      border: 1px solid #c4c4c4;
      clear: both;
      overflow: hidden;
      }
      .top_spac{ margin: 20px 0 0;}
      .recent_heading {float: left; width:40%;}
      .srch_bar {
      display: inline-block;
      text-align: right;
      width: 60%; padding:
      }
      .headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}
      .recent_heading h4 {
      color: #05728f;
      font-size: 21px;
      margin: auto;
      }
      .srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
      .srch_bar .input-group-addon button {
      background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
      border: medium none;
      padding: 0;
      color: #707070;
      font-size: 18px;
      }
      .srch_bar .input-group-addon { margin: 0 0 0 -27px;}
      .chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
      .chat_ib h5 span{ font-size:13px; float:right;}
      .chat_ib p{ font-size:14px; color:#989898; margin:auto}
      .chat_img {
      float: left;
      width: 11%;
      }
      .chat_ib {
      float: left;
      padding: 0 0 0 15px;
      width: 88%;
      }
      .chat_people{ overflow:hidden; clear:both;}
      .chat_list {
      border-bottom: 1px solid #c4c4c4;
      margin: 0;
      padding: 18px 16px 10px;
      }
      .inbox_chat { height: 550px; overflow-y: scroll;}
      .active_chat{ background:#ebebeb;}
      .incoming_msg_img {
      display: inline-block;
      width: 6%;
      }
      .received_msg {
      display: inline-block;
      padding: 0 0 0 10px;
      vertical-align: top;
      width: 92%;
      }
      .received_withd_msg p {
      background: #ebebeb none repeat scroll 0 0;
      border-radius: 3px;
      color: #646464;
      font-size: 14px;
      margin: 0;
      padding: 5px 10px 5px 12px;
      width: 100%;
      }
      .time_date {
      color: #747474;
      display: block;
      font-size: 12px;
      margin: 8px 0 0;
      }
      .received_withd_msg { width: 57%;}
      .mesgs {
      float: left;
      padding: 30px 15px 0 25px;
      width: 60%;
      }
      .sent_msg p {
      background: #05728f none repeat scroll 0 0;
      border-radius: 3px;
      font-size: 14px;
      margin: 0; color:#fff;
      padding: 5px 10px 5px 12px;
      width:100%;
      }
      .outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
      .sent_msg {
      float: right;
      width: 46%;
      }
      .input_msg_write input {
      background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
      border: medium none;
      color: #4c4c4c;
      font-size: 15px;
      min-height: 48px;
      width: 100%;
      }
      .type_msg {border-top: 1px solid #c4c4c4;position: relative;}
      .msg_send_btn {
      background: #05728f none repeat scroll 0 0;
      border: medium none;
      border-radius: 50%;
      color: #fff;
      cursor: pointer;
      font-size: 17px;
      height: 33px;
      position: absolute;
      right: 0;
      top: 11px;
      width: 33px;
      }
      .messaging { padding: 0 0 50px 0;}
      .msg_history {
      height: 516px;
      overflow-y: auto;
      }
   </style>
</html>