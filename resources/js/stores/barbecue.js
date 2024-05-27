import { defineStore } from 'pinia';
import axios from 'axios';

export const useBarbecueStore = defineStore('barbecue', {
  state: () => ({
    preInsertedBarbecues: [],
    barbecues: [],
    filters: {},
    limitPackets: 10,
    barbecue: null,
    basket: {
      basket_product: [],
    },
    products: [],
  }),
  actions: {
    setBarbecue(barbecue) {
      this.barbecue = barbecue;
      this.basket = { ...barbecue.basket };
    },
    fetchBarbecues(filters = this.filters) {
      this.filters = filters;
      axios.get('/api/barbecues', {
        params: {
          ...filters,
          limit: this.limitPackets,
        },
      }).then(response => {
        this.barbecues = response.data;
        this.preInsertedBarbecues = [];
      });
    },
    insertBarbacue(data, user) {
      this.preInsertedBarbecues.push({ ...data, user, created_at: new Date(), comments: [] });
    },
    fetchNext() {
      const offset = this.barbecues.length + this.preInsertedBarbecues.length;
      axios.get('/api/barbecues', {
        params: {
          ...this.filters,
          offset,
          limit: this.limitPackets,
        },
      }).then(response => {
        this.barbecues.push(...response.data);
      });
    },
    setBasketProduct(productId, quantity, user, name, price) {
      const product = this.basket.basket_product.find(item => item.product.id === productId);
      if (product) {
        product.quantity = quantity;
      } else {
        this.basket.basket_product.push({
          product: {
            id: productId
            , name: name
            , price: price
          }, quantity, user
        });
      }
      this.basket = { ...this.basket };
      console.log(this.basket, 'setBasketProduct');
    },
    setUserBasketProduct(productId, user) {
      // assign user to product
      const product = this.basket.basket_product.find(item => item.product.id === productId);
      if (product) {
        product.user = user;
      }
      this.basket = { ...this.basket };
      console.log(this.basket, 'setUserBasketProduct');
    },
    removeBasketProduct(productId) {
      const index = this.basket.basket_product.findIndex(item => item.product.id === productId);
      if (index !== -1) {
        this.basket.basket_product.splice(index, 1);
      }
      this.basket = { ...this.basket };
      console.log(this.basket, 'removeBasketProduct');
    },
    removeMember(memberId) {
      // this.barbecue.members = this.barbecue.members.filter(member => member.id !== memberId);

      this.barbecue.members.forEach((member, index) => {
        if (member.id === memberId) {
          this.barbecue.members.splice(index, 1);
        }
      });
    }
  },
});