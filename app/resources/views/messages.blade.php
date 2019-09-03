@extends('layouts.app')

@section('content')
    <div id="app_msg" class="container">
        <div class="row">
            <div class="col-md-3 pull-left" style="background-color:#fff;">
                <h3 align="center">Click on User</h3>
                <div v-for="privsteMsg in privsteMsgs">
                    <li v-if="privsteMsg.status==1" @click="messages(privsteMsg.id)" style="list-style:none;
      margin-top:10px; background-color:#F3F3F3;" class="row">

                        <div class="col-md-3 pull-left">
                            <img :src="'{{url('/')}}/img/' + privsteMsg.picture"
                                 style="width:50px; height: 55px; border-radius:100%; margin:5px">
                        </div>

                        <div class="col-md-9 pull-left" style="margin-top:5px">
                            <b> @{{privsteMsg.name}}</b><br>
                            <small>Gender: @{{privsteMsg.gender}}</small>
                        </div>
                    </li>

                    <li v-else @click="messages(privsteMsg.id)" style="list-style:none;
      margin-top:10px; background-color:#fff;" class="row">

                        <div class="col-md-3 pull-left">
                            <img :src="'{{url('/')}}/img/' + privsteMsg.picture"
                                 style="width:50px; height: 55px;border-radius:100%; margin:5px">
                        </div>

                        <div class="col-md-9 pull-left" style="margin-top:5px">
                            <b> @{{privsteMsg.name}}</b><br>
                            <small>Gender: @{{privsteMsg.gender}}</small>
                        </div>
                    </li>
                </div>
            </div>

            <div class="col-md-6">
                <h3 align="center">Messages</h3>
                <div style="max-height: 200px !important;  overflow-y: scroll;">
                    <div v-for="singleMsg in singleMsgs">
                        <div v-if="singleMsg.user_from == <?php echo Auth::user()->id; ?>">
                            <div class="row">
                                <div class="col-md-12" style="margin-top:10px">
                                    <img :src="'{{Config::get('/')}}/app/public/img/' + singleMsg.picture"
                                         style="width:30px; border-radius:100%; margin-left:5px; float:right;">
                                    <div style="float:right; background-color:#0084ff; padding:5px 15px 5px 15px;
          margin-right:10px;color:#333; border-radius:10px; color:#fff;">
                                        @{{singleMsg.msg}}
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div v-else>
                            <div class="row">
                                <div class="col-md-12 pull-right" style="margin-top:10px">
                                    <img :src="'{{Config::get('/')}}/app/public/img/' + singleMsg.picture"
                                         style="width:30px; border-radius:100%; margin-left:5px; float:left;">
                                    <div style="float:left; background-color:#F0F0F0; padding: 5px 15px 5px 15px;
        border-radius:10px; text-align:right; margin-left:5px ">
                                        @{{singleMsg.msg}}
                                    </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" v-model="conID">
                <textarea class="col-md-12 form-contro" style="border:none; margin-top:10px" v-model="msgFrom" @keydown="inputHandler"></textarea>
            </div>


            <div class="col-md-3 pull-right">
                <h3 align="center">User Information</h3>
                <hr>
            </div>
        </div>
    </div>
@endsection
