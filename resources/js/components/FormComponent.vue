<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="border: 1px solid #38c172">
                    <div class="card-header"><h5>Заказать меню</h5></div>

                    <div v-if="errors.length" class="alert alert-danger" role="alert">
                        <ul>
                            <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
                        </ul>
                    </div>

                    <div v-if="successMessage" class="alert alert-success" role="alert">
                        {{ successMessage }}
                    </div>

                    <form>
                        <div class="card-body row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Имя</label>
                                    <input
                                        id="name"
                                        name="name"
                                        type="text"
                                        v-model="name"
                                        class="form-control"
                                        :class="{'is-invalid': $v.name.$error, 'is-valid': $v.name.$anyDirty && !$v.name.$error}"
                                        placeholder="Имя"
                                        @blur="$v.name.$touch()"
                                    />
                                    <div class="invalid-feedback">
                                        Пожалуйста, укажите Ваше имя
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Телефон</label>
                                    <input
                                        id="phone"
                                        name="phone"
                                        type="tel"
                                        v-model="phone"
                                        v-mask="phoneMask"
                                        class="form-control"
                                        :class="{'is-invalid': $v.phone.$error, 'is-valid': $v.phone.$anyDirty && !$v.phone.$error}"
                                        placeholder="+7(***)***-**-**"
                                        @blur="$v.phone.$touch()"
                                    />
                                    <div class="invalid-feedback">
                                        Пожалуйста, укажите корректный номер телефона
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address">Адрес</label>
                                    <input
                                        id="address"
                                        name="address"
                                        type="text"
                                        v-model="address"
                                        class="form-control"
                                        :class="{'is-invalid': $v.address.$dirty && $v.address.$invalid, 'is-valid': $v.address.$anyDirty && !$v.address.$error}"
                                        placeholder="г Санкт-Петербург, пр-кт Луначарского, д 86 к 2" 
                                    />

                                    <div class="invalid-feedback">
                                        Пожалуйста, укажите Ваш адрес
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tariff">Тариф</label>
                                    <select
                                        id="tariff"
                                        name="tariff"
                                        v-model="selectedTariff"
                                        @change="loadAvailableDays"
                                        class="form-control"
                                        placeholder="Выберите тариф"
                                    >
                                        <option v-for="tariff in availableTariffs" :key="tariff.id" :value="tariff">{{ tariff.name + ' (' + tariff.price + 'руб)' }}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="deliveryDate">День доставки</label>
                                    <select
                                        id="deliveryDate"
                                        name="deliveryDate"
                                        v-model="selectedDay"
                                        class="form-control"
                                        placeholder="Выберите тариф"
                                    >
                                        <option v-for="(day, index) in availableDays" :key="index" :value="day">{{ day.name + ' (' + day.date + ')' }}</option>
                                    </select>
                                </div>

                                <div style="margin-top: 47px">
                                    <button type="button" @click="createOrder" :disabled="$v.phone.$anyError" class="btn btn-success">Заказать</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { required } from "vuelidate/lib/validators";
    import { debounce } from 'lodash';

    const mustBePhoneNumber = value => /^\+7\(\d{3}\)\d{3}\-\d{2}\-\d{2}$/i.test(value);

    export default {
        data: () => ({
            name: '',
            phone: '',
            phoneMask: '+7(###)###-##-##',
            address: '',

            availableTariffs: [],
            selectedTariff: {},
            availableDays: [],
            selectedDay: '',

            errors: [],
            successMessage: ''
        }),

        validations: {
            phone: { required, mustBePhoneNumber },
            name: { required },
            address: { required },
        },

        methods: {
            loadTariffs() {
                axios.get('/api/tariffs').then((response) => {
                    if (response.data.error) {
                        this.errors.push(response.data.error)
                    } else {
                        this.availableTariffs = response.data

                        if (this.availableTariffs.length) {
                            this.setDefaultTariff()
                        }
                    }
                }).catch((error) => {
                    this.errors.push('Произошла непредвиденная ошибка! Обратитесь в техподдержку!')
                });
            },

            setDefaultTariff() {
                this.selectedTariff = this.availableTariffs[0]

                this.loadAvailableDays()
            },

            loadAvailableDays() {
                axios.get('/api/tariffs/' + this.selectedTariff.id + '/getAvailableDays').then((response) => {
                    if (response.data.error) {
                        this.errors.push(response.data.error)
                    } else {
                        this.availableDays = response.data

                        if (this.availableDays.length) {
                            this.setDefaultDay()
                        }
                    }
                }).catch((error) => {
                    this.errors.push('Произошла непредвиденная ошибка! Обратитесь в техподдержку!')
                });
            },

            setDefaultDay() {
                this.selectedDay = this.availableDays[0]
            },

            createOrder() {
                this.errors = []
                this.successMessage = ''

                var qs = require('qs');
                
                const order = {
                    name: this.name,
                    phone: this.phone,
                    address: this.address,
                    tariff_id: this.selectedTariff.id,
                    date: this.selectedDay.date
                }

                axios({
                    data: qs.stringify(order),
                    method: "post",
                    url: "/api/order"
                })
                .then((response) => {
                    if (response.data.success) {
                        if (response.data.message) {
                            this.successMessage = response.data.message
                        } else {
                            this.successMessage = 'Ваш заказ успешно создан!'
                        }
                    }
                })
                .catch((error) => {
                    if (error.response.data.errors) {
                        for (let [key, value] of Object.entries(error.response.data.errors)) {
                            this.errors.push(value[0])
                        }
                    }
                });
            }
        },

        mounted() {
            this.loadTariffs()
        }
    }
</script>
