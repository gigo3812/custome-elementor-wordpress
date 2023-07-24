<section id="codeTrendApp">
  <div class="card">
    <!-- content -->
    <div class="content">
      <!-- exchange currency pairs -->
      <div class="exchange-currency-pairs">
        <p>تبادل جفت ارز</p>

        <div>
          <img v-if="coinSelectedToPayToBuy" class="img-get" :src="coinSelectedToPayToBuy.coinImg" alt="coin get" />
          <img v-if="coinSelectedToPay" :src="coinSelectedToPay.coinImg" alt="coin pay" />
        </div>
      </div>

      <!-- you pay -->
      <div>
        <p class="part-title">پرداخت میکنید</p>
        <!-- you pay info -->
        <div class="input-cnt">
          <!-- input: how much? -->
          <input type="text" v-model="moneyPay" placeholder="0.00" />

          <!-- select coin  -->
          <vue-multiselect class="text-right" placeholder="انتخاب ارز" v-model="coinSelectedToPay" :show-labels="false" :options="coins" :searchable="false" :allow-empty="false" :custom-label="customLabel" track-by="coin">
          </vue-multiselect>
        </div>
      </div>

      <!-- return icon -->
      <div class="return-icon" @click="changeAction">
        <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="17.5" cy="17.5" r="17.5" fill="#052B61" />
          <path d="M10.6316 23.3954C7.50331 18.4718 10.8627 12.8818 15.7863 9.75345M15.7863 9.75345L15.6707 15.0103M15.7863 9.75345L10.5295 9.6379" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          <path d="M24.5255 11.5461C28.6535 15.6677 26.5958 21.8565 22.4742 25.9844M22.4742 25.9844L21.4391 20.8292M22.4742 25.9844L27.6294 24.9493" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>

      <!-- you get -->
      <div>
        <p class="part-title">
          دریافت میکنید
        </p>
        <!-- you get info -->
        <div class="input-cnt">
          <!-- input: how much? -->
          <input type="text" v-model="youGet" placeholder="0.00" />

          <!-- select coin  -->
          <vue-multiselect class="text-right" placeholder="انتخاب ارز" v-model="coinSelectedToPayToBuy" :show-labels="false" :options="coins" :searchable="false" :allow-empty="false" :custom-label="customLabel" track-by="coin">
          </vue-multiselect>
        </div>
      </div>

      <!-- info about exchange: network fee-->
      <div class="fee-info">
        <div class="fee-text">
          <div class="info-icon">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M15 8C15 11.866 11.866 15 8 15C4.13401 15 1 11.866 1 8C1 4.13401 4.13401 1 8 1C11.866 1 15 4.13401 15 8Z" fill="white" />
              <path d="M7.3 6.6H8V11.5M15 8C15 11.866 11.866 15 8 15C4.13401 15 1 11.866 1 8C1 4.13401 4.13401 1 8 1C11.866 1 15 4.13401 15 8Z" stroke="#323E96" stroke-opacity="0.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              <circle cx="8" cy="4" r="1" fill="#323E96" fill-opacity="0.5" />
            </svg>
          </div>
          <p>
            فی شبکه
          </p>
        </div>
        <p class="fee-coin" v-if="coinSelectedToPayToBuy">
          {{coinSelectedToPay.networkFee}} {{coinSelectedToPay.FaName}}
        </p>
        <p class=" fee-coin" v-else>
          -.--
        </p>
      </div>

      <!-- action button -->
      <div v-if="false">
        <button class="w-full h-[60px] mt-5 bg-[#052B61] text-center text-white text-[20px] font-[800] border-[#052B61] rounded-md hover:bg-[#ECF1F9] hover:border-[#ECF1F9] hover:text-[#052B61] transition-all">ثبت
          سفارش
        </button>
      </div>
    </div>
  </div>
</section>