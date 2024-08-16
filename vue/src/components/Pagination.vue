<template>
	<ul class="pagination pagination-space justify-content-end mb-0">
		<li class="page-item" :class="{ disabled: isInFirstPage }">
			<button type="button" class="page-link" @click.prevent="onClickFirstPage">
				First
			</button>
		</li>
		<li class="page-item" :class="{ disabled: isInFirstPage }">
			<button type="button" class="page-link" @click.prevent="onClickPreviousPage">
				Previous
			</button>
		</li>
		<li v-for="(page, key) in pages" class="page-item" :key="key" :class="{ active: isPageActive(page.name) }">
			<button type="button" class="page-link" @click.prevent="onClickPage(page.name)" :disabled="page.isDisabled">
				{{ page.name }}
			</button>
		</li>
		<li class="page-item" :class="{ disabled: isInLastPage }">
			<button type="button" class="page-link" @click.prevent="onClickNextPage">
				Next
			</button>
		</li>
		<li class="page-item" :class="{ disabled: isInLastPage }">
			<button type="button" class="page-link" @click.prevent="onClickLastPage">
				Last
			</button>
		</li>
	</ul>
</template>

<script>
export default {
	name: 'Pagination',
	props: {
		maxPage: {
			type: Number,
			required: true
		},
		totalPages: {
			type: Number,
			required: true
		},
		currentPage: {
			type: Number,
			required: true
		},
	},

	computed: {
		startPage() {
			if (this.currentPage === 1) {
				return 1;
			}
			if (this.currentPage === this.totalPages) {
				return this.totalPages - this.maxPage + 1;
			}
			return this.currentPage - 1;
		},
		endPage() {
			return Math.min(this.startPage + this.maxPage - 1, this.totalPages);
		},
		pages() {
			const range = [];
			for (let i = this.startPage; i <= this.endPage; i += 1) {
				range.push({
					name: i,
					isDisabled: i === this.currentPage
				});
			}
			return range;
		},
		isInFirstPage() {
			return this.currentPage === 1;
		},
		isInLastPage() {
			return this.currentPage === this.totalPages;
		},
	},
	methods: {
		onClickFirstPage() {
			this.$emit('pagechanged', 1);
		},
		onClickPreviousPage() {
			this.$emit('pagechanged', this.currentPage - 1);
		},
		onClickPage(page) {
			this.$emit('pagechanged', page);
		},
		onClickNextPage() {
			this.$emit('pagechanged', this.currentPage + 1);
		},
		onClickLastPage() {
			this.$emit('pagechanged', this.totalPages);
		},
		isPageActive(page) {
			return this.currentPage === page;
		},
	}
}
</script>