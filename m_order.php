
<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
<section class="content container-fluid" id="app">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{ en_name }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="btn-group" style="padding: 10px;">
              <!-- <a id="save_info" class="btn btn-success" onclick="save_info();">Save</a>
              <a id="app_info" class="btn btn-success" onclick="approve();">Approve</a>
              <a id="search_info" class="btn btn-primary" onclick="window.open('search_m_store.php?IDF=Master', 'mywin', 'width=800, height=700');" class="btn btn-info btn-sm">Search</a>
              <a id="cancel_info" class="btn btn-danger" onclick="">Cancel</a> -->
              
              <!-- <a id="store_info" class="btn btn-success" onclick="view_stores();">View Stores</a> -->
              
            </div>


                
            <!-- form start -->
            <form role="form">
              <div class="box-body col-md-12">


              
                <table id="exampletable" class='table table-bordered' style="width:100%">
                    <thead>
                        <tr>
                            <th>Order Reference</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Date</th>
                            <th>Phone</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Routes</th>
                            <th>Assign</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tfoot>
                      <tr v-for="order in ORDERS">
                            <td>{{ order.REF }}</td>
                            <td>{{ order.address }}</td>
                            <td>{{ order.city }}</td>
                            <td>{{ order.date }}</td>
                            <td>{{ order.phone }}</td>
                            <td>{{ order.reg_name }}</td>
                            <td>{{ order.tot }}</td>
                            <td><a id="store_info" class="btn btn-success btn-sm" @click="view_stores(order.REF)">View</a></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>




                


              </div>
              <!-- /.box-body -->

              <div class="box-footer">

              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (left) -->
       
       


      </div>

</section>
<script src="js/m_order.js"></script>




