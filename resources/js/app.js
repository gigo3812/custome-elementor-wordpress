import { createApp } from 'vue'

//**MultiSelect */
import vueMultiselect from 'vue-multiselect'

const App = createApp({
  data() {
    return {
      activeSlider: false,

      //** money pay input */
      moneyPay: '',

      //**Multiselect value and options */
      coinSelectedToPay: { id: 2,asset:'435.21',networkFee:'1', coin: 'USDT', coinImg:'http://localhost:8080/assets/img/coins/USDT.svg', min:'20', FaName:'تتر'  },
      coins: [
        { id: 1,asset:'1.035',networkFee:'0.0003', coin: 'BTC', coinImg: 'http://localhost:8080/assets/img/coins/BTC.svg', min:'0.005', FaName:'بیت کوین' },
        { id: 2,asset:'435.21',networkFee:'0.03', coin: 'USDT', coinImg:'http://localhost:8080/assets/img/coins/USDT.svg', min:'20', FaName:'تتر' },
        { id: 3,asset:'1523052',networkFee:'750', coin: 'TMN', coinImg: 'http://localhost:8080/assets/img/coins/tmn.png', min:'200,000', FaName:'تومان' },
        { id: 4,asset:'14.23',networkFee:'0.01', coin: 'SOL', coinImg: 'http://localhost:8080/assets/img/coins/Solana.svg', min:'0.5', FaName:'سولانا' },
        { id: 5,asset:'65.22',networkFee:'0.005', coin: 'ADA', coinImg: 'http://localhost:8080/assets/img/coins/ADA.svg', min:'15', FaName:'کاردانو' },
        { id: 6,asset:'125.3',networkFee:'0.001', coin: 'XRP', coinImg: 'http://localhost:8080/assets/img/coins/XRP.svg', min:'23', FaName:'ریپل' },
      ],

      selectedCoinId: null,
      coinSelectedToPayToBuy:'',
      youGet:null

      //**all assets */
    }
  },
  methods: {
    customLabel ({ coin }) {
      return `${coin}`
    },
    selectAssets(){
      this.moneyPay = this.coinSelectedToPay.asset
    },
    changeAction() {
      if (this.coinSelectedToPayToBuy && this.coinSelectedToPay) {
        const temp = this.coinSelectedToPayToBuy;
        this.coinSelectedToPayToBuy = this.coinSelectedToPay;
        this.coinSelectedToPay = temp;
      }
    },
  },
  mounted() {
    console.log('run vue 3');
  },
  components: {
    vueMultiselect,
  },
})
App.mount('#codeTrendApp')