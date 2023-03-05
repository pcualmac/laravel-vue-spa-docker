import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'
import middleware from './middlewares/middleware'
import ensureCsrfTokenSet from './middlewares/ensureCsrfTokenSet'
import guest from './middlewares/guest'
import authenticated from './middlewares/authenticated';

Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',
	base: process.env.BASE_URL,
	scrollBehavior() {
		return {x: 0, y: 0}
	},
	routes: [
		{
			path: '/login',
			name: 'login',
			component: () => import('@/views/Login.vue'),
			meta: {
				layout: 'full',
				middleware: [ensureCsrfTokenSet, guest],
				title: 'Login'
			},
		},
		{
			path: '/',
			name: 'home',
			component: () => import('@/views/Home.vue'),
			meta: {
				middleware: [ensureCsrfTokenSet, authenticated],
				title: 'Home',
				breadcrumb: [
					{
						text: 'Home',
						active: true,
					},
				],
			},
		},
		{
			path: '/error-404',
			name: 'error-404',
			component: () => import('@/views/error/Error404.vue'),
			meta: {
				layout: 'full',
			},
		},
		{
			path: '*',
			redirect: 'error-404',
		},
	],
})

router.beforeEach(middleware({store}))

// router.beforeEach(async (to, from, next) => {
// 	document.title = to.meta.title;
// })


// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
	// Remove initial loading
	const appLoading = document.getElementById('loading-bg')
	if (appLoading) {
		appLoading.style.display = 'none'
	}
})

export default router