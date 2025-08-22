<template>
  <BContainer>
    <BRow>
      <BCol
        xxl="12"
        class="mt-5"
      >
        <BCard no-body>
          <BCardBody class="checkout-tab">
            <div class="row">
              <div class="col text-center my-4">
                <img
                  src="../../assets/logos/logo-grande.png"
                  alt=""
                  height="80"
                >
              </div>
            </div>

            <div
              id="custom-progress-bar"
              class="progress-nav mb-4"
            >
              <div
                class="progress"
                style="height: 1px;"
              >
                <div
                  class="progress-bar"
                  role="progressbar"
                  :style="`width: ${(steps.length - 1) * 25}%;`"
                  aria-valuenow="0"
                  aria-valuemin="0"
                  aria-valuemax="100"
                />
              </div>

              <ul
                class="nav nav-pills progress-bar-tab custom-nav"
                role="tablist"
              >
                <li
                  v-for="(step, index) in steps"
                  :key="`li-step-${index}`"
                  class="nav-item"
                  role="presentation"
                >
                  <button
                    id="pills-gen-info-tab"
                    class="nav-link rounded-pill"
                    type="button"
                    role="tab"
                    :class="{ active: activeTab == index, done: activeTab > index }"
                  >
                    <i
                      :class="[
                        activeTab >= index ? 'text-white' : 'text-primary',
                        step,
                        'fs-16',
                        'avatar-xs d-inline-flex align-items-center justify-content-center',
                        'bg-soft-primary',
                        'rounded-circle',
                        'align-middle',
                        'me-2',
                      ]"
                    />
                  </button>
                </li>
              </ul>
            </div>

            <div class="tab-content">
              <div
                id="pills-gen-info"
                class="tab-pane fade"
                :class="activeTab == 0 && 'show active'"
                role="tabpanel"
                aria-labelledby="pills-gen-info-tab"
              >
                <div>
                  <div class="mb-4">
                    <div>
                      <h5 class="mb-1">
                        Detalhes do Gabinete
                      </h5>
                    </div>
                  </div>
                  <div id="details">
                    <div class="row">
                      <div class="col-md-5 mb-3">
                        <label for="nameResponsible">Nome do Vereador
                          <span
                            class="text-danger"
                          >*
                          </span>
                        </label>
                        <input
                          id="nameResponsible"
                          v-model="formData.nameResponsible"
                          name="nameResponsible"
                          placeholder="Nome do Vereador"
                          type="text"
                          class="form-control"
                          required
                        >
                      </div>
                      <div class="col-md-3 mb-3">
                        <label for="cpf">CPF
                          <span class="text-danger">*</span>
                        </label>
                        <input
                          id="cpf"
                          v-model="formData.cpf"
                          v-maska="'###.###.###-##'"
                          name="cpf"
                          placeholder="___.___.___-__"
                          type="text"
                          class="form-control"
                          required
                        >
                      </div>
                      <div class="col-md-2 mb-3">
                        <div class="form-group">
                          <label for="zipCode">CEP
                            <span
                              class="text-danger"
                            >*
                            </span>
                          </label>
                          <input
                            id="zipCode"
                            v-model="formData.zipCode"
                            v-maska="'##.###-###'"
                            class="form-control"
                            name="zipCode"
                            type="text"
                            placeholder="CEP"
                            required
                            @keyup="setAddress"
                          >
                        </div>
                      </div>
                      <div class="col-md-2 mb-3">
                        <div class="form-group">
                          <label for="uf">Estado
                            <span
                              class="text-danger"
                            >*
                            </span>
                          </label>
                          <input
                            id="uf"
                            v-model="formData.uf"
                            type="text"
                            name="uf"
                            class="form-control"
                            placeholder="UF"
                            required
                          >
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 mb-3">
                        <div class="form-group">
                          <label for="city">Cidade
                            <span
                              class="text-danger"
                            >*
                            </span>
                          </label>
                          <input
                            id="city"
                            v-model="formData.city"
                            type="text"
                            name="city"
                            class="form-control"
                            placeholder="Cidade"
                            required
                          >
                        </div>
                      </div>
                      <div class="col-md-3 mb-3">
                        <div class="form-group">
                          <label for="neighborhood">Bairro
                            <span
                              class="text-danger"
                            >*
                            </span>
                          </label>
                          <input
                            id="neighborhood"
                            v-model="formData.neighborhood"
                            type="text"
                            name="neighborhood"
                            class="form-control"
                            placeholder="Bairro"
                            required
                          >
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="address">Endereço
                            <span
                              class="text-danger"
                            >*
                            </span>
                          </label>
                          <input
                            id="address"
                            v-model="formData.address"
                            type="text"
                            name="address"
                            class="form-control"
                            placeholder="Endereço"
                            required
                          >
                        </div>
                      </div>
                      <div class="col-md-3 mb-3">
                        <div class="form-group">
                          <label for="number">Número
                            <span
                              class="text-danger"
                            >*
                            </span>
                          </label>
                          <input
                            id="number"
                            v-model="formData.number"
                            type="text"
                            name="number"
                            class="form-control"
                            placeholder="Bairro"
                            required
                          >
                        </div>
                      </div>
                      <div class="col-md-3 mb-3">
                        <div class="form-group">
                          <label for="complement">Complemento</label>
                          <input
                            id="complement"
                            v-model="formData.complement"
                            type="text"
                            name="complement"
                            class="form-control"
                            placeholder="Bairro"
                          >
                        </div>
                      </div>
                      <div class="col-md-3 mb-3">
                        <label for="emailCabinet">E-mail do Gabinete
                          <span
                            class="text-danger"
                          >*
                          </span>
                        </label>
                        <input
                          id="emailCabinet"
                          v-model="formData.emailCabinet"
                          type="text"
                          name="emailCabinet"
                          class="form-control"
                          placeholder="E-mail"
                          required
                        >
                      </div>
                      <div class="col-md-3 mb-3">
                        <label for="phone">Telefone
                          <span
                            class="text-danger"
                          >*
                          </span>
                        </label>
                        <input
                          id="phone"
                          v-model="formData.phone"
                          v-maska="['(##) ####-####', '(##) #####-####']"
                          type="text"
                          name="phone"
                          class="form-control"
                          placeholder="(__) ____-____"
                          required
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div
                id="pills-info-desc"
                class="tab-pane fade"
                :class="activeTab == 1 && 'show active'"
                role="tabpanel"
                aria-labelledby="pills-info-desc-tab"
              >
                <div>
                  <div class="mb-4">
                    <div>
                      <h5 class="mb-1">
                        Dados de Acesso
                      </h5>
                    </div>
                  </div>

                  <div id="access">
                    <div class="row">
                      <div class="col-md-3 mb-3">
                        <label for="name">Nome
                          <span class="text-danger">*</span>
                        </label>
                        <input
                          id="name"
                          v-model="formData.name"
                          name="name"
                          placeholder="Nome"
                          type="text"
                          class="form-control"
                          required
                        >
                      </div>
                      <div class="col-md-3 mb-3">
                        <label for="email">E-mail
                          <span class="text-danger">*</span>
                        </label>
                        <input
                          id="email"
                          v-model="formData.email"
                          name="email"
                          placeholder="E-mail"
                          type="email"
                          class="form-control"
                          required
                        >
                      </div>
                      <div class="col-md-3 mb-3">
                        <label for="password">Senha
                          <span class="text-danger">*</span>
                        </label>
                        <input
                          id="password"
                          v-model="formData.password"
                          name="password"
                          placeholder="Senha"
                          type="password"
                          class="form-control"
                          required
                        >
                        <span style="font-size: 8px; color: grey; margin-left: 5px">Mínimo 8
                          caracteres
                        </span>
                      </div>
                      <div class="col-md-3 mb-3">
                        <label for="password2">Confirme a Senha
                          <span
                            class="text-danger"
                          >*
                          </span>
                        </label>
                        <input
                          id="password2"
                          v-model="formData.password2"
                          name="password2"
                          placeholder="Confirme a Senha"
                          type="password"
                          class="form-control"
                          required
                        >
                        <span style="font-size: 8px; color: grey; margin-left: 5px">Mínimo 8
                          caracteres
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div
                id="pills-info-desc"
                class="tab-pane fade"
                :class="activeTab == 2 && 'show active'"
                role="tabpanel"
                aria-labelledby="pills-info-desc-tab"
              >
                <div>
                  <div class="mb-4">
                    <div>
                      <h5 class="mb-1">
                        Planos e Preços
                      </h5>
                    </div>
                  </div>

                  <div id="payment">
                    <BRow class="g-4">
                      <BCol sm="6">
                        <div>
                          <div class="form-check card-radio">
                            <input
                              id="paymentMethod01"
                              v-model="formData.payment_method"
                              name="paymentMethod"
                              type="radio"
                              class="form-check-input"
                              :checked="formData.payment_method === 'bar_code'"
                              value="bar_code"
                            >
                            <label
                              class="form-check-label"
                              for="paymentMethod01"
                            >
                              <div class="pricing-box ribbon-box right">
                                <div class="p-4 m-2">
                                  <div>
                                    <div class="d-flex align-items-center">
                                      <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold">Pro
                                          Business</h5>
                                        <p class="text-muted mb-0">Professional
                                          plans</p>
                                      </div>
                                      <div class="avatar-sm">
                                        <div
                                          class="
                                                                                          avatar-title
                                                                                          bg-light
                                                                                          rounded-circle
                                                                                          text-primary
                                                                                        "
                                        >
                                          <i class="ri-medal-line fs-20" />
                                        </div>
                                      </div>
                                    </div>

                                    <div class="pt-4">
                                      <h2>
                                        <sup><small>$</small></sup> 297
                                        <span class="fs-13 text-muted">/Month
                                        </span>
                                      </h2>
                                    </div>
                                  </div>
                                  <hr class="my-4 text-muted">
                                  <div>
                                    <ul class="list-unstyled vstack gap-3 text-muted">
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-success me-1">
                                            <i class="ri-checkbox-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1">Upto
                                            <b>15</b> Projects
                                          </div>
                                        </div>
                                      </li>
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-success me-1">
                                            <i class="ri-checkbox-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1">
                                            <b>Unlimited</b> Customers
                                          </div>
                                        </div>
                                      </li>
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-success me-1">
                                            <i class="ri-checkbox-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1">Scalable
                                            Bandwidth
                                          </div>
                                        </div>
                                      </li>
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-success me-1">
                                            <i class="ri-checkbox-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1"><b>12</b>
                                            FTP Login
                                          </div>
                                        </div>
                                      </li>
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-success me-1">
                                            <i class="ri-checkbox-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1"><b>24/7</b>
                                            Support
                                          </div>
                                        </div>
                                      </li>
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-danger me-1">
                                            <i class="ri-close-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1"><b>Unlimited</b>
                                            Storage
                                          </div>
                                        </div>
                                      </li>
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-danger me-1">
                                            <i class="ri-close-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1">Domain
                                          </div>
                                        </div>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </label>
                          </div>
                        </div>
                      </BCol>
                      <BCol sm="6">
                        <div>
                          <div class="form-check card-radio">
                            <input
                              id="paymentMethod02"
                              v-model="formData.payment_method"
                              name="paymentMethod"
                              type="radio"
                              class="form-check-input"
                              value="credit_card"
                              :checked="formData.payment_method === 'credit_card'"
                            >
                            <label
                              class="form-check-label"
                              for="paymentMethod02"
                            >
                              <div class="pricing-box ribbon-box right">
                                <div class="p-4 m-2">
                                  <div>
                                    <div class="d-flex align-items-center">
                                      <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold">Pro
                                          Business</h5>
                                        <p class="text-muted mb-0">Professional
                                          plans</p>
                                      </div>
                                      <div class="avatar-sm">
                                        <div
                                          class="
                                                                                          avatar-title
                                                                                          bg-light
                                                                                          rounded-circle
                                                                                          text-primary
                                                                                        "
                                        >
                                          <i class="ri-medal-line fs-20" />
                                        </div>
                                      </div>
                                    </div>

                                    <div class="pt-4">
                                      <h2>
                                        <sup><small>$</small></sup> 597
                                        <span class="fs-13 text-muted">/Month
                                        </span>
                                      </h2>
                                    </div>
                                  </div>
                                  <hr class="my-4 text-muted">
                                  <div>
                                    <ul class="list-unstyled vstack gap-3 text-muted">
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-success me-1">
                                            <i class="ri-checkbox-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1">Upto
                                            <b>15</b> Projects
                                          </div>
                                        </div>
                                      </li>
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-success me-1">
                                            <i class="ri-checkbox-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1">
                                            <b>Unlimited</b> Customers
                                          </div>
                                        </div>
                                      </li>
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-success me-1">
                                            <i class="ri-checkbox-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1">Scalable
                                            Bandwidth
                                          </div>
                                        </div>
                                      </li>
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-success me-1">
                                            <i class="ri-checkbox-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1"><b>12</b>
                                            FTP Login
                                          </div>
                                        </div>
                                      </li>
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-success me-1">
                                            <i class="ri-checkbox-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1"><b>24/7</b>
                                            Support
                                          </div>
                                        </div>
                                      </li>
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-danger me-1">
                                            <i class="ri-close-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1"><b>Unlimited</b>
                                            Storage
                                          </div>
                                        </div>
                                      </li>
                                      <li>
                                        <div class="d-flex">
                                          <div class="flex-shrink-0 text-danger me-1">
                                            <i class="ri-close-circle-fill fs-15 align-middle" />
                                          </div>
                                          <div class="flex-grow-1">Domain
                                          </div>
                                        </div>
                                      </li>
                                    </ul>

                                  </div>
                                </div>
                              </div>
                            </label>
                          </div>
                        </div>
                      </BCol>
                    </BRow>
                  </div>
                </div>
              </div>

              <div
                id="pills-info-desc"
                class="tab-pane fade"
                :class="activeTab == 3 && 'show active'"
                role="tabpanel"
                aria-labelledby="pills-info-desc-tab"
              >
                <div>
                  <div class="mb-4">
                    <div>
                      <h5 class="mb-1">
                        Detalhes de Pagamento
                      </h5>
                    </div>
                  </div>

                  <div id="payment">
                    <BRow class="g-4">
                      <BCol sm="6">
                        <div>
                          <div class="form-check card-radio">
                            <input
                              id="paymentMethod01"
                              v-model="formData.payment_method"
                              name="paymentMethod"
                              type="radio"
                              class="form-check-input"
                              value="bar_code"
                            >
                            <label
                              class="form-check-label"
                              for="paymentMethod01"
                            >
                              <span class="fs-16 text-muted me-2">
                                <i class="ri-barcode-box-fill align-bottom" />
                              </span>
                              <span class="fs-14 text-wrap">Boleto Recorrente</span>
                            </label>
                          </div>
                        </div>
                      </BCol>
                      <BCol sm="6">
                        <div>
                          <div class="form-check card-radio">
                            <input
                              id="paymentMethod02"
                              v-model="formData.payment_method"
                              name="paymentMethod"
                              type="radio"
                              class="form-check-input"
                              value="credit_card"
                              checked
                            >
                            <label
                              class="form-check-label"
                              for="paymentMethod02"
                            >
                              <span class="fs-16 text-muted me-2">
                                <i class="ri-bank-card-fill align-bottom" />
                              </span>
                              <span class="fs-14 text-wrap">
                                Cartão de Crédito / Débito
                              </span>
                            </label>
                          </div>
                        </div>
                      </BCol>
                    </BRow>
                    <div
                      class="accordion"
                      role="tablist"
                    >
                      <div
                        v-if="collapseBar"
                        class="mt-2"
                      >
                        <BCard
                          no-body
                          class="p-4 border shadow-none mb-0 mt-4"
                        >
                          <BRow class="gy-3">
                            <BCol md="12">
                              <label
                                for="cc-name"
                                class="form-label"
                              >Nome</label>
                              <input
                                id="cc-name"
                                type="text"
                                class="form-control"
                                placeholder="Digite o nome"
                              >
                              <small class="text-muted">
                                Nome completo como exibido no cartão
                              </small>
                            </BCol>
                          </BRow>
                        </BCard>
                      </div>


                      <div
                        v-if="collapseCard"
                        class="mt-2"
                      >
                        <BCard
                          no-body
                          class="p-4 border shadow-none mb-0 mt-4"
                        >
                          <BRow class="gy-3">
                            <BCol md="12">
                              <label
                                for="cc-name"
                                class="form-label"
                              >Nome no cartão</label>
                              <input
                                id="cc-name"
                                type="text"
                                class="form-control"
                                placeholder="Digite o nome"
                              >
                              <small class="text-muted">Nome completo como exibido no
                                cartão</small>
                            </BCol>

                            <BCol md="6">
                              <label
                                for="cc-number"
                                class="form-label"
                              >Número do cartão
                                de crédito</label>
                              <input
                                id="cc-number"
                                v-maska="'#### #### #### ####'"
                                type="text"
                                class="form-control"
                                placeholder="xxxx xxxx xxxx xxxx"
                              >
                            </BCol>

                            <BCol md="3">
                              <label
                                for="cc-expiration"
                                class="form-label"
                              >Validade</label>
                              <input
                                id="cc-expiration"
                                v-maska="'##/##'"
                                type="text"
                                class="form-control"
                                placeholder="MM/AA"
                              >
                            </BCol>

                            <BCol md="3">
                              <label
                                for="cc-cvv"
                                class="form-label"
                              >CVV</label>
                              <input
                                id="cc-cvv"
                                v-maska="'###'"
                                type="text"
                                class="form-control"
                                placeholder="xxx"
                              >
                            </BCol>
                          </BRow>
                        </BCard>
                        <div class="text-muted mt-2 fst-italic">
                          <i
                            data-feather="lock"
                            class="text-muted icon-xs"
                          />
                          Sua transação está protegida com criptografia SSL
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div
                id="pills-success"
                class="tab-pane fade"
                :class="activeTab == 4 && 'show active'"
                role="tabpanel"
                aria-labelledby="pills-success-tab"
              >
                <div>
                  <div class="text-center pt-4 pb-2">
                    <div class="pb-5">
                      <div class="avatar-md mx-auto">
                        <div class="mb-4">
                          <lottie
                            colors="primary:#0ab39c,secondary:#405189"
                            :options="defaultOptions"
                            :height="120"
                            :width="120"
                          />
                        </div>
                      </div>
                    </div>
                    <h2 class="mb-3">
                      Cadastro realizado com sucesso!
                    </h2>

                    <p class="mb-4">
                      Sua conta foi criada e está pronta para uso. Agora você pode acessar
                      todos os recursos da nossa plataforma.
                    </p>

                    <div class="d-grid gap-2 col-md-6 mx-auto">
                      <router-link
                        class="btn btn-soft-primary"
                        to="/login"
                      >
                        Fazer Login
                      </router-link>
                    </div>

                    <div class="mt-4 text-muted small">
                      <p>
                        Enviamos um e-mail de confirmação para o endereço cadastrado.
                        <br>
                        Caso não encontre, verifique sua pasta de spam.
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-between mt-4">
                <div>
                  <button
                    v-if="activeTab > 0"
                    type="button"
                    class="btn btn-link text-decoration-none btn-label previestab"
                    @click="--activeTab"
                  >
                    <i class="ri-arrow-left-line label-icon align-middle fs-lg me-2" />
                    Voltar
                  </button>
                </div>
                <div>
                  <button
                    v-if="activeTab < 4"
                    type="button"
                    class="btn btn-success btn-label right ms-auto nexttab nexttab"
                    @click="nextTab"
                  >
                    <i class="ri-arrow-right-line label-icon align-middle fs-lg ms-2" />
                    Próximo
                  </button>
                </div>
              </div>
            </div>
          </BCardBody>
        </BCard>
      </BCol>
    </BRow>
  </BContainer>
</template>

<script setup>
import Lottie from "@/components/widgets/lottie.vue";
import animationData from "@/components/widgets/lupuorrc.json";

const defaultOptions = ref({animationData: animationData});
import {ref, watch} from 'vue';
import http from '@/http'
import {toFormData, ValidateForm} from "@/composables/cruds";
import {endLoading, startLoading} from "@/composables/spinners";
import {notifyError} from "@/composables/messages";
import $ from "jquery";

const activeTab = ref(0);
const collapseCard = ref(true);
const collapseBar = ref(false);

const steps = ['ri-building-4-line', 'ri-user-3-line', 'ri-money-dollar-circle-line', 'ri-bank-card-line', 'ri-checkbox-circle-line']

const formData = ref({
    id: 0,
    nameResponsible: '',
    cpf: '',
    zipCode: '',
    uf: '',
    city: '',
    neighborhood: '',
    address: '',
    emailCabinet: '',
    phone: '',
    name: '',
    email: '',
    password: '',
    password2: '',
    type: 1,
    payment_method: 'credit_card',
    plan: 'credit_card',
})

watch(() => formData.value, () => {
    collapseCard.value = formData.value.payment_method === 'credit_card';
    collapseBar.value = formData.value.payment_method === 'bar_code';
}, {deep: true, immediate: true})

function nextTab() {
    let form = 'details';

    if (activeTab.value === 1) {
        form = 'access'
    } else if (activeTab.value === 2) {
        form = 'payment'
    }


    if (!ValidateForm(form)) {
        endLoading(form, 'save');
        return;
    }

    if (activeTab.value === 3) {
        startLoading(form, 'save')
        const newFormData = toFormData(formData.value);
        http.post('gabinete/cadastrar', newFormData)
            .then(() => {
                ++activeTab.value;
            })
            .catch(e => {
                notifyError(e.response.data);
            })
            .finally(() => {
                endLoading(form, 'save');
            })
    } else {
        ++activeTab.value
        endLoading(form, 'save');
    }
}

async function setAddress() {
    var cep = formData.value.zipCode.replace(/\D/g, '');
    var validacep = /^[0-9]{8}$/;
    if (validacep.test(cep)) {
        await $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {
            if (!("erro" in dados)) {

                formData.value.uf = dados.uf;
                formData.value.city = dados.localidade;
                formData.value.neighborhood = dados.bairro;
                formData.value.address = dados.logradouro;
                formData.value.complement = dados.complemento;
            }
        });
    }
}
</script>

