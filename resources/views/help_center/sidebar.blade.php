<div class="nk-entry-sidebar">
                  @foreach($side_menu as $menu)
                  <ul class="nk-list-link nk-list-link-collapse flush">
                    <li>
                      <span  class="nk-list-link-collapse-link gap-2" data-bs-toggle="collapse" aria-expanded="true">
                        <span>{{ $menu->title }}</span>
                        <em class="on icon ni ni-plus"></em>
                        <em class="off icon ni ni-minus"></em>
                      </span>

                      @foreach(\App\Models\Help::subMenus($menu->id) as $sub_menu)
                      <div class="collapse show" id="BillingAndPayments">
                        <ul class="nk-list-dot nk-list-dot-sm pt-2">
                          <li class="nk-list-dot-item">
                            <a href="/help/{{ $sub_menu->slug }}" @if (Str::startsWith(url()->current(), route('help', $sub_menu->slug))) style="color:#3f53d8 !important" @endif>{{ $sub_menu->title }}</a>
                          </li>
                        </ul>
                      </div>
                      @endforeach
                    </li>
                  </ul>
                  @endforeach
                </div>