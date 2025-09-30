import { createStore } from 'vuex'
import ApiService from '../services/ApiService'

export default createStore({
  state: {
    user: null,
    token: localStorage.getItem('token') || null,
    savedRecipes: [],
    mealPlans: [],
    loading: false,
    error: null
  },

  mutations: {
    SET_USER(state, user) {
      state.user = user
    },
    SET_TOKEN(state, token) {
      state.token = token
      if (token) {
        localStorage.setItem('token', token)
        ApiService.setAuthToken(token)
      } else {
        localStorage.removeItem('token')
        ApiService.setAuthToken(null)
      }
    },
    SET_SAVED_RECIPES(state, recipes) {
      state.savedRecipes = recipes
    },
    ADD_SAVED_RECIPE(state, recipe) {
      state.savedRecipes.push(recipe)
    },
    REMOVE_SAVED_RECIPE(state, recipeId) {
      state.savedRecipes = state.savedRecipes.filter(r => r.id !== recipeId)
    },
    SET_MEAL_PLANS(state, plans) {
      state.mealPlans = plans
    },
    ADD_MEAL_PLAN(state, plan) {
      state.mealPlans.push(plan)
    },
    UPDATE_MEAL_PLAN(state, updatedPlan) {
      const index = state.mealPlans.findIndex(p => p.id === updatedPlan.id)
      if (index !== -1) {
        state.mealPlans.splice(index, 1, updatedPlan)
      }
    },
    REMOVE_MEAL_PLAN(state, planId) {
      state.mealPlans = state.mealPlans.filter(p => p.id !== planId)
    },
    SET_LOADING(state, loading) {
      state.loading = loading
    },
    SET_ERROR(state, error) {
      state.error = error
    },
    CLEAR_ERROR(state) {
      state.error = null
    }
  },

  actions: {
    async login({ commit }, credentials) {
      try {
        commit('SET_LOADING', true)
        commit('CLEAR_ERROR')

        const response = await ApiService.login(credentials)
        const { user, token } = response.data

        commit('SET_USER', user)
        commit('SET_TOKEN', token)
        commit('SET_SAVED_RECIPES', user.recipes || [])
        commit('SET_MEAL_PLANS', user.plans || [])

        return response
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Login failed')
        throw error
      } finally {
        commit('SET_LOADING', false)
      }
    },

    async register({ commit }, userData) {
      try {
        commit('SET_LOADING', true)
        commit('CLEAR_ERROR')

        const response = await ApiService.register(userData)
        const { user, token } = response.data

        commit('SET_USER', user)
        commit('SET_TOKEN', token)
        commit('SET_SAVED_RECIPES', user.recipes || [])
        commit('SET_MEAL_PLANS', user.plans || [])

        return response
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Registration failed')
        throw error
      } finally {
        commit('SET_LOADING', false)
      }
    },

    async logout({ commit }) {
      try {
        await ApiService.logout()
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        commit('SET_USER', null)
        commit('SET_TOKEN', null)
        commit('SET_SAVED_RECIPES', [])
        commit('SET_MEAL_PLANS', [])
      }
    },

    async updateUser({ commit }, userData) {
      try {
        commit('SET_LOADING', true)
        const response = await ApiService.updateUser(userData)
        commit('SET_USER', response.data)
        return response
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Update failed')
        throw error
      } finally {
        commit('SET_LOADING', false)
      }
    },

    async saveRecipe({ commit }, recipe) {
      try {
        const response = await ApiService.createRecipe(recipe)
        commit('ADD_SAVED_RECIPE', response.data.recipe)
        if (response.data.plan) {
          commit('ADD_MEAL_PLAN', response.data.plan)
        }
        return response
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Failed to save recipe')
        throw error
      }
    },

    async deleteRecipe({ commit }, recipeId) {
      try {
        await ApiService.deleteRecipe(recipeId)
        commit('REMOVE_SAVED_RECIPE', recipeId)
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Failed to delete recipe')
        throw error
      }
    },

    async createPlan({ commit }, planData) {
      try {
        const response = await ApiService.createPlan(planData)
        commit('ADD_MEAL_PLAN', response.data)
        return response
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Failed to create plan')
        throw error
      }
    },

    async updatePlan({ commit }, { planId, planData }) {
      try {
        const response = await ApiService.updatePlan(planId, planData)
        commit('UPDATE_MEAL_PLAN', response.data)
        return response
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Failed to update plan')
        throw error
      }
    },

    async deletePlan({ commit }, planId) {
      try {
        commit('REMOVE_MEAL_PLAN', planId)
        await ApiService.deletePlan(planId)
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Failed to delete plan')
        throw error
      }
    }
  },

  getters: {
    isAuthenticated: state => !!state.token,
    user: state => state.user,
    savedRecipes: state => state.savedRecipes,
    mealPlans: state => state.mealPlans,
    loading: state => state.loading,
    error: state => state.error
  }
})
