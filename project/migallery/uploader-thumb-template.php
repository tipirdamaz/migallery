              <!-- sortable area -->
              <div id="sortFile" class="js-files b-upload__files row d-none ml-auto ms-auto mr-auto me-auto mb-2 w-100 border">

                <!-- new selected files template -->
                <div class="col thumb file-tpl" id="<%=uid%>" data-id="<%=uid%>" title="<%-name%>, <%-sizeText%>">

                  <div class="card my-3"><!-- card -->

                    <div class="card-top row w-100 ml-auto ms-auto mr-auto me-auto">
                      <div class="img-number" id="img_number_<%=uid%>"></div>                    	
                      <div class="thumb__del del_new_uploaded" id="del_new_<%=uid%>" style="display:none" onclick="Uploader.imageDelUploaded('<%=uid%>','<%=type%>')" title="<?php echo $lang->uploader->uploader->thumb->new->delete;?>"></div>
                      <div data-fileapi="file.remove" class="b-thumb__del" title="<?php echo $lang->uploader->uploader->thumb->new->cancel;?>"></div>
                    </div>

                    <div class="thumb-top-bar row w-100 ml-auto ms-auto mr-auto me-auto"></div>

                    <div class="card-img-body">
                      <div class="move_thumb"></div>
                      <div class="b-thumb__preview js-preview" id="thumb_<%=uid%>">
                        <div class="b-thumb__preview__pic" title="<?php echo $lang->uploader->uploader->thumb->move;?>"></div>
                      </div>
                      <div class="b-thumb__progress progress progress-small"><div class="bar"></div></div>
                    </div>
                    <div class="card-body thumb_queue_bg">

                  <div class="thumb-bottom-bar row w-100 ml-auto ms-auto mr-auto me-auto">

                    <div class="col-4 p-0">
                      <% if( /^image/.test(type) ){ %>
                        <div data-fileapi="file.rotate.cw" class="b-thumb__rotate" title="<?php echo $lang->uploader->uploader->thumb->new->rotate;?>"></div>
                      <% } %>
                    </div>

                    <div class="col-4 p-0">
                      <div class="b-thumb__crop" onclick="Uploader.imageCrop('<%=uid%>')" title="<?php echo $lang->uploader->uploader->thumb->new->crop;?>"></div>
                    </div>

                    <div class="col-4 p-0 d-flex justify-content-end">
                      <div class="b-thumb__info" title="<?php echo $lang->uploader->uploader->thumb->new->info;?>" onclick="Uploader.imageInfoShow('<%=uid%>', 'new')"></div>
                    </div>
               	
                  </div>

                  <div class="row w-100 ml-auto ms-auto mr-auto me-auto">
                    <div class="col-12 p-0">
                      <div class="thumb__name"><%-name%></div>
                    </div>
                  </div>

                    </div>
                  </div><!-- card -->
                  <input type="hidden" id="type<%=uid%>" value="<%=type%>">
                  <input type="hidden" id="size<%=uid%>" value="<%-sizeText%>">
                  <input type="hidden" id="title<%=uid%>" value="">
                  <input type="hidden" id="desc<%=uid%>" value="">
                  <input type="hidden" id="keyw<%=uid%>" value="">
                  <input type="hidden" id="time<%=uid%>" value="">
                  <input type="hidden" id="up<%=uid%>" value="new">
                </div>
                <!--/ new selected files template -->

              </div>
              <!--/ sortable area -->
