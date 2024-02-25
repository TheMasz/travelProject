<div class="modal">
    <div class="modal_content">
        <div class="content_wrap">
            <form action="/api/addPref" method="post">
                @csrf
                <div class="top">
                    <h3>โปรดบอกเราว่าคุณชอบอะไร</h3>
                    <p style="color: lightgray; line-height: 140%; font-weight: 400; font-size: 14px;">
                        เพื่อประสบการณ์ท่องเที่ยวที่ดียิ่งขึ้น (หากเลือกไม่ครบระบบจะเลือกเป็นค่ากลางให้)</p>
                </div>
                <!-- message alert -->
                @if (session('warning'))
                    <div class="message message-warning mb-05">
                        {{ session('warning') }}
                    </div>
                @endif
                <div class="center">
                    <ul>
                        @foreach ($preferences as $preference)
                            <li>
                                <p>{{ $preference->preference_name }}</p>
                                <div class="row py-02">
                                    <label class="preference-label">
                                        <input type="radio" name="preference_{{ $preference->preference_id }}"
                                            value="1" class="hidden-checkbox">
                                        <span class="material-icons icon worst">
                                            sentiment_very_dissatisfied
                                        </span>
                                    </label>
                                    <label class="preference-label">
                                        <input type="radio" name="preference_{{ $preference->preference_id }}"
                                            value="2" class="hidden-checkbox">
                                        <span class="material-icons icon bad">
                                            sentiment_dissatisfied
                                        </span>
                                    </label>
                                    <label class="preference-label">
                                        <input type="radio" name="preference_{{ $preference->preference_id }}"
                                            value="3" class="hidden-checkbox">
                                        <span class="material-icons icon avg">
                                            sentiment_neutral
                                        </span>
                                    </label>
                                    <label class="preference-label">
                                        <input type="radio" name="preference_{{ $preference->preference_id }}"
                                            value="4" class="hidden-checkbox">
                                        <span class="material-icons icon good">
                                            sentiment_satisfied
                                        </span>
                                    </label>
                                    <label class="preference-label">
                                        <input type="radio" name="preference_{{ $preference->preference_id }}"
                                            value="5" class="hidden-checkbox">
                                        <span class="material-icons icon best">
                                            sentiment_very_satisfied
                                        </span>
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bottom">
                    <button type="submit" class="btn-primary">เสร็จสิ้น</button>
                </div>
            </form>
        </div>
    </div>
</div>
