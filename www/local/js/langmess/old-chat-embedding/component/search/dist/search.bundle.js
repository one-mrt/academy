/* eslint-disable */
this.BX = this.BX || {};
this.BX.Messenger = this.BX.Messenger || {};
this.BX.Messenger.Embedding = this.BX.Messenger.Embedding || {};
(function (
		exports,
		ui_designTokens,
		ui_fonts_opensans,
		main_core_events,
		im_oldChatEmbedding_const,
		im_oldChatEmbedding_component_elements,
		im_oldChatEmbedding_lib_menu,
		im_oldChatEmbedding_lib_utils,
		main_core, 
		im_oldChatEmbedding_lib_logger,
		ui_dexie,
		im_oldChatEmbedding_application_core
	) {
	'use strict';

	class SearchContextMenu extends im_oldChatEmbedding_lib_menu.RecentMenu {
		getMenuItems() {
			return [this.getSendMessageItem(), this.getCallItem(), this.getHistoryItem(), this.getOpenProfileItem()];
		}
	}

	const CarouselUser = {
		name: 'CarouselUser',
		components: {
			Avatar: im_oldChatEmbedding_component_elements.Avatar
		},
		props: {
			user: {
				type: Object,
				required: true
			}
		},
		computed: {
			name() {
				return this.user.dialog.name.split(' ')[0];
			},
			isExtranet() {
				return this.user.user.extranet;
			},
			AvatarSize: () => im_oldChatEmbedding_component_elements.AvatarSize
		},
		created() {
			this.contextMenuManager = new SearchContextMenu(this.$Bitrix);
		},
		beforeUnmount() {
			this.contextMenuManager.destroy();
		},
		methods: {
			onClick() {
				main_core_events.EventEmitter.emit(im_oldChatEmbedding_const.EventType.dialog.open, {
					dialogId: this.user.dialogId,
					chat: this.user.dialog,
					user: this.user.user
				});
				BX.MessengerProxy.clearSearchInput();
			},
			onRightClick(event) {
				if (event.altKey && event.shiftKey) {
					return;
				}
				const item = {
					dialogId: this.user.dialogId
				};
				main_core_events.EventEmitter.emit(im_oldChatEmbedding_const.EventType.search.openContextMenu, {
					item,
					event
				});
			}
		},
		template: `
		<div class="bx-messenger-carousel-item" @click="onClick" @click.right.prevent="onRightClick">
			<Avatar :dialogId="user.dialogId" :size="AvatarSize.L" />
			<div :class="[isExtranet ? 'bx-messenger-carousel-item-extranet' : '', 'bx-messenger-carousel-item-title']">
				{{name}}
			</div>
		</div>
	`
	};

	const recentUsersLimit = 5;

	// @vue/component
	const RecentUsersCarousel = {
		name: 'RecentUsersCarousel',
		components: {
			CarouselUser
		},
		props: {
			title: {
				type: String,
				required: false,
				default: ''
			}
		},
		computed: {
			users() {
				const recentUsers = [];
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				store.getters['recent/getSortedCollection'].forEach(recentItem => {
					const dialog = store.getters['dialogues/get'](recentItem.dialogId, true);
					const user = store.getters['users/get'](recentItem.dialogId, true);
					recentUsers.push({
						...recentItem,
						dialog,
						user
					});
				});
				const usersWithoutBotsAndCurrentUser = recentUsers.filter(item => {
					return item.dialog.type === 'user' && !item.user.bot && item.user.id !== this.currentUserId;
				});
				return usersWithoutBotsAndCurrentUser.slice(0, recentUsersLimit);
			},
			currentUserId() {
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				return store.state.application.common.userId;
			}
		},
		// language=Vue
		template: `
		<div v-if="title" class="bx-messenger-recent-users-carousel-title">{{title}}</div>
		<div class="bx-messenger-recent-users-carousel">
			<CarouselUser v-for="user in users" :key="user.dialogId" :user="user" />
		</div>
	`
	};

	const SearchResultSection = {
		name: 'SearchResultSection',
		props: {
			component: {
				type: Object,
				required: true
			},
			items: {
				type: Object,
				// Map<string, SearchItem>
				required: true
			},
			title: {
				type: String,
				required: true
			},
			showMoreButton: {
				type: Boolean,
				default: true,
				required: false
			},
			minItems: {
				type: Number,
				default: 10,
				required: false
			},
			maxItems: {
				type: Number,
				default: 50,
				required: false
			}
		},
		data: function () {
			return {
				expanded: false
			};
		},
		computed: {
			showMore() {
				if (!this.showMoreButton) {
					return false;
				}
				return this.items.size > this.minItems;
			},
			showMoreButtonText() {
				return this.expanded ? this.$Bitrix.Loc.getMessage('IM_SEARCH_SECTION_TITLE_SHOW_LESS') : this.$Bitrix.Loc.getMessage('IM_SEARCH_SECTION_TITLE_SHOW_MORE');
			},
			sectionItems() {
				const itemsFromMap = [...this.items.values()];
				if (!this.showMoreButton) {
					return itemsFromMap;
				}
				return this.expanded ? itemsFromMap.slice(0, this.maxItems) : itemsFromMap.slice(0, this.minItems);
			}
		},
		methods: {
			onShowMore() {
				this.expanded = !this.expanded;
			}
		},
		template: `
		<div class="w-100 h-100 d-f fd-c">
			<div class="w-100 pby-8 pix-16 c-8c94a8 f-12 bbw-1 bbs-s bbc-525c6914">{{title}}</div>
			<component :is="component" v-for="item in sectionItems" :key="item.getEntityFullId()" :item="item" />
			<div class="w-100 d-f jc-c pby-8">
				<div v-if="showMore" class="pix-12 pby-4 bc-py c-w m-0 bw-1 bs-s bcr-py r-999 lh-1 f-12 tt-l h:bc-w h:c-dt tn-all-3 pn-r z-2" @click.prevent="onShowMore">
					{{ showMoreButtonText }}
				</div>
			</div>
		</div>
	`
	};

	const EntityIdTypes = Object.freeze({
		user: 'user',
		langUser: 'lang-user',
		bot: 'im-bot',
		chat: 'im-chat',
		chatUser: 'im-chat-user',
		department: 'department',
		network: 'imbot-network',
		lines: 'imol-chat'
	});

	const SearchUtils = {
		getWordsFromString(string) {
			const clearedString = string.replaceAll('(', ' ').replaceAll(')', ' ').replaceAll('[', ' ').replaceAll(']', ' ').replaceAll('{', ' ').replaceAll('}', ' ').replaceAll('<', ' ').replaceAll('>', ' ').replaceAll('-', ' ').replaceAll('#', ' ').replaceAll('"', ' ').replaceAll('\'', ' ').replace(/\s\s+/g, ' ');
			return clearedString.split(' ').filter(word => word !== '');
		},
		getTypeByEntityId(entityId) {
			switch (entityId) {
				case EntityIdTypes.user:
				case EntityIdTypes.bot:
				case EntityIdTypes.langUser:
					return 'user';
				case EntityIdTypes.chat:
				case EntityIdTypes.chatUser:
				case EntityIdTypes.lines:
					return 'chat';
				case EntityIdTypes.department:
					return 'department';
				case EntityIdTypes.network:
					return 'network';
				default:
					throw new Error(`Unknown entity id: ${entityId}`);
			}
		},
		createItemMap(items) {
			const map = new Map();
			items.forEach(item => {
				const mapItem = new SearchItem(item);
				map.set(mapItem.getEntityFullId(), mapItem);
			});
			return map;
		},
		getFirstItemFromMap(map) {
			const iterator = map.entries();
			const firstIteration = iterator.next();
			const firstItem = firstIteration.value;
			const [, content] = firstItem;
			return content;
		},
		convertKeysToLowerCase(object) {
			const result = {};
			Object.keys(object).forEach(key => {
				if (main_core.Type.isObject(object[key]) && !main_core.Type.isArray(object[key])) {
					result[key.toLowerCase()] = this.convertKeysToLowerCase(object[key]);
				} else {
					result[key.toLowerCase()] = object[key];
				}
			});
			return result;
		},
		prepareRecentItems(recentItems) {
			if (!recentItems) {
				return [];
			}
			return recentItems.map(item => {
				const [entityId, id] = item;
				const type = SearchUtils.getTypeByEntityId(entityId);
				return {
					cacheId: `${type}|${id}`,
					date: new Date()
				};
			});
		}
	};

	class SearchItem {
		entityId = null;
		entityType = null;
		dialogId = null;
		title = null;
		subtitle = null;
		name = null;
		lastName = null;
		secondName = null;
		position = null;
		avatar = null;
		avatarOptions = null;
		customSort = 0;
		contextSort = 0;
		rawData = null;
		constructor(itemOptions) {
			this.setRawData(itemOptions);
			this.setId(itemOptions);
			this.setDialogId(itemOptions);
			this.setEntityId(itemOptions);
			this.setEntityType(itemOptions);
			this.setTitle(itemOptions);
			this.setSubtitle(itemOptions);
			this.setName(itemOptions);
			this.setLastName(itemOptions);
			this.setSecondName(itemOptions);
			this.setPosition(itemOptions);
			this.setAvatar(itemOptions);
			this.setAvatarOptions(itemOptions);
			this.setContextSort(itemOptions);
		}
		isFromProviderResponse(itemOptions) {
			return main_core.Type.isString(itemOptions.entityId) && !main_core.Type.isNil(itemOptions.id);
		}
		isFromModel(itemOptions) {
			return main_core.Type.isString(itemOptions.dialogId) && main_core.Type.isObject(itemOptions.dialog);
		}
		setId(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				this.id = itemOptions.id;
			} else if (this.isFromModel(itemOptions)) {
				this.id = itemOptions.dialogId.startsWith('chat') ? itemOptions.dialogId.slice(4) : itemOptions.dialogId;
			}
		}
		setDialogId(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				if (itemOptions.customData?.imChat?.ID > 0) {
					this.dialogId = `chat${itemOptions.customData.imChat.ID}`;
				} else if (itemOptions.customData?.imUser?.ID > 0) {
					this.dialogId = itemOptions.customData.imUser.ID.toString();
				}
			} else if (this.isFromModel(itemOptions)) {
				this.dialogId = itemOptions.dialogId;
			}
		}
		setEntityId(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				this.entityId = itemOptions.entityId;
			} else if (this.isFromModel(itemOptions)) {
				if (!itemOptions.user) {
					this.entityId = EntityIdTypes.chat;
				} else if (itemOptions.user.bot) {
					this.entityId = EntityIdTypes.bot;
				} else {
					this.entityId = EntityIdTypes.user;
				}
			}
		}
		setEntityType(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				this.entityType = itemOptions.entityType;
			}
		}
		setTitle(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				this.title = itemOptions.title;
			} else if (this.isFromModel(itemOptions)) {
				this.title = itemOptions.dialog.name;
			}
		}
		setSubtitle(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				this.subtitle = itemOptions.subtitle;
			}
		}
		setName(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				this.name = itemOptions.customData?.name;
			} else if (this.isFromModel(itemOptions)) {
				this.name = itemOptions.user?.firstName;
			}
		}
		setLastName(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				this.lastName = itemOptions.customData?.lastName;
			} else if (this.isFromModel(itemOptions)) {
				this.lastName = itemOptions.user?.lastName;
			}
		}
		setSecondName(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				this.secondName = itemOptions.customData?.secondName;
			}
		}
		setPosition(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				this.position = itemOptions.customData?.position;
			} else if (this.isFromModel(itemOptions)) {
				this.position = itemOptions.user?.workPosition;
			}
		}
		setAvatar(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				this.avatar = itemOptions.avatar;
			}
		}
		setAvatarOptions(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				this.avatarOptions = itemOptions.avatarOptions;
			}
		}
		setContextSort(itemOptions) {
			if (this.isFromProviderResponse(itemOptions)) {
				this.contextSort = itemOptions.contextSort;
			}
		}
		setRawData(itemOptions) {
			this.rawData = itemOptions;
		}
		getId() {
			return this.id;
		}
		getEntityId() {
			return this.entityId;
		}
		getEntityType() {
			return this.entityType;
		}
		getEntityFullId() {
			const type = SearchUtils.getTypeByEntityId(this.entityId);
			return `${type}|${this.id}`;
		}
		getTitle() {
			return this.title;
		}
		getSubtitle() {
			return this.subtitle;
		}
		getName() {
			return this.name;
		}
		getLastName() {
			return this.lastName;
		}
		getSecondName() {
			return this.secondName;
		}
		getPosition() {
			return this.position;
		}
		getCustomData() {
			return this.rawData.customData;
		}
		getDialogId() {
			return this.dialogId;
		}
		getAvatar() {
			return this.avatar;
		}
		getAvatarOptions() {
			return this.avatarOptions;
		}
		getContextSort() {
			return this.contextSort ? this.contextSort : 0;
		}
		addCustomSort(value) {
			this.customSort += value;
		}
		getCustomSort() {
			return this.customSort;
		}
		isUser() {
			if (this.isFromProviderResponse(this.rawData)) {
				return !!this.rawData.customData?.imUser && this.rawData.customData.imUser.ID > 0;
			}
			return !!this.rawData.user;
		}
		isChat() {
			return !this.isUser();
		}
		isLines() {
			return this.isOpeLinesType();
		}
		isExtranet() {
			if (this.isFromProviderResponse(this.rawData)) {
				return !!this.rawData.customData?.imUser?.EXTRANET || !!this.rawData.customData?.imChat?.EXTRANET;
			} else if (this.isFromModel(this.rawData)) {
				return !!this.rawData.user?.extranet || !!this.rawData.dialog.extranet;
			}
			return false;
		}
		getUserCustomData() {
			return this.rawData.customData?.imUser ? this.rawData.customData.imUser : null;
		}
		getChatCustomData() {
			return this.rawData.customData?.imChat ? this.rawData.customData.imChat : null;
		}
		isOpeLinesType() {
			return this.getEntityType() === 'LINES';
		}
		getOpenlineEntityId() {
			if (!this.isOpeLinesType()) {
				return '';
			}
			const entityId = this.rawData.customData?.imChat?.ENTITY_ID;
			return entityId.toString().split('|')[0];
		}
		getAvatarColor() {
			let color = '';
			if (this.isFromProviderResponse(this.rawData)) {
				if (this.isUser()) {
					color = this.rawData.customData?.imUser?.COLOR?.toString();
				} else if (this.isChat()) {
					color = this.rawData.customData?.imChat?.COLOR?.toString();
				}
			} else if (this.isFromModel(this.rawData)) {
				color = this.rawData.dialog.color.toString();
			}
			return color;
		}
		isCrmSession() {
			if (this.isFromProviderResponse(this.rawData) && this.isOpeLinesType()) {
				const sessionData = this.rawData.customData?.imChat?.ENTITY_DATA_1.toString().split('|');
				return sessionData[0] === 'Y';
			}
			return false;
		}
	}

	const SearchResultItem = {
		name: 'SearchResultItem',
		components: {
			Avatar: im_oldChatEmbedding_component_elements.Avatar,
			ChatTitle: im_oldChatEmbedding_component_elements.ChatTitle
		},
		props: {
			item: {
				type: Object,
				required: true
			},
			child: {
				type: Boolean,
				default: false,
				required: false
			}
		},
		computed: {
			dialogId() {
				return this.item.getDialogId();
			},
			user() {
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				let user = store.getters['users/get'](this.dialogId, true)

				return user;
			},
			dialog() {
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				let dialog = store.getters['dialogues/get'](this.dialogId, true)

				return dialog
			},
			isChat() {
				return !this.isUser;
			},
			isUser() {
				if(Number(this?.user?.id) > 1) {
					return true;
				}

				return false
			},
			userItemText() {
				if (!this.isUser) {
					return '';
				} else {
					let store = im_oldChatEmbedding_application_core.Core.getStore();
					let user = store.getters['users/get'](this.dialogId)

					if(user) {
						// if (user?.status) {
						// 	return user?.status;
						// }
						return "Личный чат"

						return user?.position;
					}
				}

				return ''
			},
			chatItemText() {
				if (this.isUser) {
					return '';
				}

				// if (this?.dialog?.type === im_oldChatEmbedding_const.DialogType.open) {
				// 	return this.$Bitrix.Loc.getMessage('IM_SEARCH_ITEM_CHAT_TYPE_OPEN');
				// }

				return "Чат задачи"//this.$Bitrix.Loc.getMessage('IM_SEARCH_ITEM_CHAT_TYPE_GROUP');
			},
			searchEntityId() {
				if (this.isUser) {
					return this.user.bot ? 'im-bot' : 'user';
				}

				return 'im-chat';
			},
			searchItemId() {
				if (this.dialogId.startsWith('chat')) {
					return Number.parseInt(this.dialogId.slice(4), 10);
				}

				return Number.parseInt(this.dialogId, 10);
			},
			AvatarSize: () => im_oldChatEmbedding_component_elements.AvatarSize
		},
		methods: {
			onClick(event) {
				const selectedItem = {
					id: this.searchItemId,
					entityId: this.searchEntityId,
					dialogId: this.dialogId
				};
				main_core_events.EventEmitter.emit(im_oldChatEmbedding_const.EventType.search.selectItem, {
					selectedItem: selectedItem,
					onlyOpen: false,
					nativeEvent: event
				});
			},
			onRightClick(event) {
				if (event.altKey && event.shiftKey) {
					return;
				}
				const item = {
					dialogId: this.dialogId
				};
				main_core_events.EventEmitter.emit(im_oldChatEmbedding_const.EventType.search.openContextMenu, {
					item,
					event
				});
			}
		},
		template: `
		<div @click="onClick" @click.right.prevent="onRightClick" class="bx-im-recent-item" :class="[this.child ? 'bx-im-search-sub-item' : '']">
			<div class="bx-im-search-avatar-wrap">
				<Avatar :dialogId="dialogId" :size="AvatarSize.L" />
			</div>

			<div v-if="isUser" class="bx-im-recent-item-content d-f fd-c">
				<ChatTitle :dialogId="dialogId" />
				<div class="bx-im-recent-item-content-bottom">
					<div class="bx-im-recent-item-bottom-subtitle">
						<div class="bx-im-recent-item-bottom-subtitle-text">
							{{ userItemText }} 
						</div>
					</div>
				</div>
			</div>

			<div v-else class="bx-im-recent-item-content d-f fd-c">
				<ChatTitle :dialogId="dialogId" />
				<div class="bx-im-recent-item-content-bottom">
					<div class="bx-im-recent-item-bottom-subtitle">
						<div class="bx-im-recent-item-bottom-subtitle-text">{{ chatItemText }}</div>
					</div>
				</div>
			</div>
		</div>
	`
	};

	class SearchCache {
		constructor(userId) {
			this.userId = userId;
			/** @type {Dexie} */
			this.db = new ui_dexie.Dexie('bx-im-search-results');
			this.db.version(2).stores({
				items: 'id, *title, *name, *lastName, *secondName, *position, date',
				recentItems: '++id, cacheId, date',
				settings: '&name'
			}).upgrade(transaction => {
				const clearItemsPromise = transaction.table('items').clear();
				const clearRecentItemsPromise = transaction.table('recentItems').clear();
				return ui_dexie.Dexie.Promise.all([clearItemsPromise, clearRecentItemsPromise]);
			});
			this.db.version(3).stores({
				items: 'id, *title, *name, *lastName, *position, date',
				recentItems: '++id, cacheId, date',
				settings: '&name'
			});
			this.checkTables();
			this.onAccessDeniedHandler = this.onAccessDenied.bind(this);
			main_core_events.EventEmitter.subscribe(im_oldChatEmbedding_const.EventType.dialog.errors.accessDenied, this.onAccessDeniedHandler);
		}
		checkTables() {
			this.db.open();
			this.db.on('ready', () => {
				return this.db.transaction('rw', this.db.settings, this.db.items, this.db.recentItems, () => {
					return this.db.settings.where('name').equals('userId').first();
				}).then(settings => {
					const promises = [];
					if (settings?.value !== this.userId) {
						const clearItemsPromise = this.db.items.clear();
						const clearRecentItemsPromise = this.db.recentItems.clear();
						promises.push(clearItemsPromise, clearRecentItemsPromise);
					}
					return ui_dexie.Dexie.Promise.all(promises);
				}).then(() => {
					return this.db.settings.put({
						name: 'userId',
						value: this.userId
					});
				});
			});
		}
		destroy() {
			main_core_events.EventEmitter.unsubscribe(im_oldChatEmbedding_const.EventType.dialog.errors.accessDenied, this.onAccessDeniedHandler);
		}
		loadRecentFromCache() {
			const searchResults = {};
			return this.db.transaction('rw', this.db.items, this.db.recentItems, () => {
				return this.deleteExpiredItems().then(() => {
					return this.db.recentItems.orderBy('id').toArray();
				});
			}).then(recentItemsFromCache => {
				searchResults.recentItems = recentItemsFromCache;
				const resultItemsPromises = [];
				searchResults.recentItems.forEach(recentItem => {
					resultItemsPromises.push(this.db.items.get({
						id: recentItem.cacheId
					}));
				});
				return ui_dexie.Dexie.Promise.all(resultItemsPromises);
			}).then(result => {
				searchResults.items = result.filter(item => !main_core.Type.isUndefined(item)).map(item => item.json);
				return searchResults;
			});
		}
		save(searchResults) {
			const preparedItems = searchResults.items ? this.prepareItems(searchResults.items) : [];
			const preparedRecentItems = searchResults.recentItems ? SearchUtils.prepareRecentItems(searchResults.recentItems) : [];
			this.db.transaction('rw', this.db.items, this.db.recentItems, () => {
				if (preparedItems.length > 0) {
					this.db.items.bulkPut(preparedItems);
				}
				if (preparedRecentItems.length > 0) {
					this.db.recentItems.clear().then(() => {
						this.db.recentItems.bulkPut(preparedRecentItems);
					});
				}
			});
		}
		deleteExpiredItems() {
			const oneMonthAgo = new Date(Date.now() - 60 * 60 * 1000 * 24 * 7 * 30);
			return this.db.items.where('date').below(oneMonthAgo).delete().then(() => {
				return this.db.recentItems.where('date').below(oneMonthAgo).delete();
			});
		}
		onAccessDenied({
			data: eventData
		}) {
			const cacheId = this.convertDialogIdToCacheItemId(eventData.dialogId);
			return this.db.items.where('id').equals(cacheId).delete().then(() => {
				return this.db.recentItems.where('cacheId').equals(cacheId).delete();
			});
		}
		convertDialogIdToCacheItemId(dialogId) {
			if (dialogId.startsWith('chat')) {
				return `chat|${dialogId.slice(4)}`;
			}
			return `user|${dialogId}`;
		}
		prepareItems(items) {
			return items.filter(item => {
				return item.entityId !== EntityIdTypes.department && item.entityId !== EntityIdTypes.network && item.entityType !== 'LINES';
			}).map(item => {
				const type = SearchUtils.getTypeByEntityId(item.entityId);
				return {
					id: `${type}|${item.id}`,
					name: item.customData?.name ? SearchUtils.getWordsFromString(item.customData.name) : [],
					lastName: item.customData?.lastName ? SearchUtils.getWordsFromString(item.customData.lastName) : [],
					position: item.customData.imUser?.WORK_POSITION ? SearchUtils.getWordsFromString(item.customData.imUser?.WORK_POSITION) : [],
					title: item.title ? SearchUtils.getWordsFromString(item.title) : [],
					json: item,
					date: new Date()
				};
			});
		}

		/**
		 * Moves item to the top of the recent search items list.
		 *
		 * @param itemToMove Array<string, number>
		 */
		unshiftItem(itemToMove) {
			const [itemToMoveEntityId, itemToMoveId] = itemToMove;
			const type = SearchUtils.getTypeByEntityId(itemToMoveEntityId);
			const itemToMoveCacheId = `${type}|${itemToMoveId}`;
			this.db.transaction('rw', this.db.recentItems, () => {
				return this.db.recentItems.toArray();
			}).then(recentItems => {
				const itemIndexToUpdate = recentItems.findIndex(recentItem => {
					return recentItem.cacheId === itemToMoveCacheId;
				});
				if (itemIndexToUpdate === 0) {
					return;
				}
				if (itemIndexToUpdate !== -1) {
					const item = recentItems.splice(itemIndexToUpdate, 1);
					item[0].date = new Date();
					recentItems.unshift(item[0]);
				} else {
					const item = {
						cacheId: `${itemToMoveCacheId}|${itemToMoveId}`,
						date: new Date()
					};
					recentItems.unshift(item);
				}
				recentItems.forEach(item => delete item.id);
				this.db.recentItems.clear().then(() => {
					this.db.recentItems.bulkPut(recentItems);
				});
			});
		}
		search(words) {
			return this.db.transaction('r', this.db.items, function* () {
				// Parallel search for all words - just select resulting primary keys
				const results = yield this.getQueryResultByWords(words);
				if (!main_core.Type.isArrayFilled(results)) {
					return [];
				}
				const intersectedResult = this.intersectArrays(...results);
				const distinctIds = [...new Set(intersectedResult.flat())];

				// Finally, select entire items from intersection
				return yield this.db.items.where(':id').anyOf(distinctIds).toArray();
			}.bind(this)).then(items => {
				return items.map(item => item.json);
			});
		}
		getQueryResultByWords(words) {
			return ui_dexie.Dexie.Promise.all(words.map(word => {
				return this.db.items.where('name').startsWithIgnoreCase(word).or('lastName').startsWithIgnoreCase(word).or('position').startsWithIgnoreCase(word).or('title').startsWithIgnoreCase(word).distinct().primaryKeys();
			}));
		}
		intersectArrays(firstArray, secondArray, ...restArrays) {
			if (main_core.Type.isUndefined(secondArray)) {
				return firstArray;
			}
			const intersectedArray = firstArray.filter(value => secondArray.includes(value));
			if (restArrays.length === 0) {
				return intersectedArray;
			}
			return this.intersectArrays(intersectedArray, ...restArrays);
		}
	}

	const Config = {
		get: () => {
			return {
				dialog: {
					entities: [
						{
							id: 'lang-user',
							dynamicLoad: true,
							dynamicSearch: true,
						},
						{
							id: 'im-chat',
							options: {
								searchableChatTypes: ['C', 'L', 'O'],
								fillDialogWithDefaultValues: false,
							},
							dynamicLoad: true,
							dynamicSearch: true
						},
					],
					enableSearch: true,
					preselectedItems: [],
					clearUnavailableItems: false,
					context: 'IM_CHAT_SEARCH',
					id: 'im-search'
				}
			};
		}
	};

	class SearchRecentList {
		constructor($Bitrix) {
			this.store = im_oldChatEmbedding_application_core.Core.getStore()
		}

		// region public methods
		search(queryWords) {
			const recentListItems = this.getRecentListItems();
			const foundItems = [];
			recentListItems.forEach(recentListItem => {
				if (this.searchByQueryWords(recentListItem, queryWords)) {
					foundItems.push(recentListItem);
				}
			});
			return Promise.resolve(SearchUtils.createItemMap(foundItems));
		}
		//endregion

		getRecentListItems() {
			return this.store.getters['recent/getSortedCollection'].map(item => {
				const dialog = this.store.getters['dialogues/get'](item.dialogId, true);
				const isUser = dialog.type === im_oldChatEmbedding_const.DialogType.user;
				const recentListItem = {
					dialogId: item.dialogId,
					dialog: dialog
				};
				if (isUser) {
					recentListItem.user = this.store.getters['users/get'](item.dialogId, true);
				}
				return recentListItem;
			});
		}
		searchByQueryWords(recentListItem, queryWords) {
			if (recentListItem.user) {
				return this.searchByUserFields(recentListItem, queryWords);
			}
			return this.searchByDialogFields(recentListItem, queryWords);
		}
		searchByDialogFields(recentListItem, queryWords) {
			const searchField = [];
			if (recentListItem.dialog.name) {
				const dialogNameWords = SearchUtils.getWordsFromString(recentListItem.dialog.name.toLowerCase());
				searchField.push(...dialogNameWords);
			}
			return this.doesItemMatchQuery(searchField, queryWords);
		}
		searchByUserFields(recentListItem, queryWords) {
			const searchField = [];
			if (recentListItem.user.firstName) {
				const userFirstNameWords = SearchUtils.getWordsFromString(recentListItem.user.firstName.toLowerCase());
				searchField.push(...userFirstNameWords);
			}
			if (recentListItem.user.lastName) {
				const userLastNameWords = SearchUtils.getWordsFromString(recentListItem.user.lastName.toLowerCase());
				searchField.push(...userLastNameWords);
			}
			if (recentListItem.user.workPosition) {
				const userWorkPositionWords = SearchUtils.getWordsFromString(recentListItem.user.workPosition.toLowerCase());
				searchField.push(...userWorkPositionWords);
			}
			return this.doesItemMatchQuery(searchField, queryWords);
		}
		doesItemMatchQuery(fieldsForSearch, queryWords) {
			let found = 0;
			queryWords.forEach(queryWord => {
				let queryWordsMatchCount = 0;
				fieldsForSearch.forEach(field => {
					if (field.startsWith(queryWord)) {
						queryWordsMatchCount++;
					}
				});
				if (queryWordsMatchCount > 0) {
					found++;
				}
			});
			return found >= queryWords.length;
		}
	}

	const RestMethodImopenlinesNetworkJoin = 'imopenlines.network.join';
	class SearchService {
		static instance = null;
		store = null;
		cache = null;
		recentList = null;
		static getInstance($Bitrix, cache, recentList) {
			if (!this.instance) {
				this.instance = new this($Bitrix, cache, recentList);
			}

			return this.instance;
		}
		constructor($Bitrix, cache, recentList) { 
			this.store = im_oldChatEmbedding_application_core.Core.getStore()
			this.cache = cache;
			this.recentList = recentList;
			this.restClient = $Bitrix.RestClient.get();
			this.applicationLayout = $Bitrix.Application.get().params.layout;
			this.onItemSelectHandler = this.onItemSelect.bind(this);
			this.onOpenNetworkItemHandler = this.onOpenNetworkItem.bind(this);

			main_core_events.EventEmitter.subscribe(im_oldChatEmbedding_const.EventType.search.selectItem, this.onItemSelectHandler);
			// main_core_events.EventEmitter.subscribe(im_oldChatEmbedding_const.EventType.search.openNetworkItem, this.onOpenNetworkItemHandler);
		}

		//region Public methods

		loadRecentSearchFromCache() {
			return this.cache.loadRecentFromCache().then(responseFromCache => {
				im_oldChatEmbedding_lib_logger.Logger.warn('Im.Search: Recent search loaded from cache');

				return responseFromCache;
			}).then(responseFromCache => {
				const {items, recentItems} = responseFromCache;
				const itemMap = SearchUtils.createItemMap(items);
				const filteredItems = this.#filterResult(itemMap);

				return this.updateModels(filteredItems).then(() => {
					return this.getItemsFromRecentItems(recentItems, filteredItems);
				});
			});
		}
		loadRecentSearchFromServer() {
			return this.loadRecentFromServer().then(responseFromServer => {
				im_oldChatEmbedding_lib_logger.Logger.warn('Im.Search: Recent search loaded from server');
				const items = SearchUtils.createItemMap(responseFromServer.items);
				const recentItems = SearchUtils.prepareRecentItems(responseFromServer.recentItems);
				const filteredItems = this.#filterResult(items);

				return this.updateModels(filteredItems, true).then(() => {
					return this.getItemsFromRecentItems(recentItems, filteredItems);
				});
			});
		}
		searchLocal(query) {
			const originalLayoutQuery = query.trim().toLowerCase();
			const searchInCachePromise = this.searchInCache(originalLayoutQuery);
			const searchInRecentListPromise = this.searchInRecentList(originalLayoutQuery);
			return Promise.all([searchInCachePromise, searchInRecentListPromise]).then(result => {
				// Spread order is important, because we have more data in cache than in recent list
				// (for example contextSort field)
				const items = new Map([...result[1], ...result[0]]);
				return this.getSortedItems(items, originalLayoutQuery);
			});
		}
		searchOnServer(query, config) {
			const originalLayoutQuery = query.trim().toLowerCase();
			let items = [];
			return this.searchRequest(originalLayoutQuery, config).then(itemsFromServer => {
				items = SearchUtils.createItemMap(itemsFromServer);
				items = this.#filterResult(items);
				return this.updateModels(items, true);
			}).then(() => {
				return this.allocateSearchResults(items, originalLayoutQuery);
			});
		}
		searchOnNetwork(query) {
			const originalLayoutQuery = query.trim().toLowerCase();
			return this.searchOnNetworkRequest(originalLayoutQuery).then(items => {
				return SearchUtils.createItemMap(items);
			});
		}
		loadDepartmentUsers(parentItem) {
			let items = [];
			return this.loadDepartmentUsersFromServer(parentItem).then(responseFromServer => {
				items = SearchUtils.createItemMap(responseFromServer);
				return this.updateModels(items, true);
			}).then(() => {
				return items;
			});
		}
		destroy() {
			this.cache.destroy();
			main_core_events.EventEmitter.unsubscribe(im_oldChatEmbedding_const.EventType.search.selectItem, this.onItemSelectHandler);
			main_core_events.EventEmitter.unsubscribe(im_oldChatEmbedding_const.EventType.search.openNetworkItem, this.onOpenNetworkItemHandler);
		}

		//endregion

		searchInCache(originalLayoutQuery) {
			let wrongLayoutSearchPromise = Promise.resolve([]);
			if (this.needLayoutChange(originalLayoutQuery)) {
				const wrongLayoutQuery = this.changeLayout(originalLayoutQuery);
				wrongLayoutSearchPromise = this.getItemsFromCacheByQuery(wrongLayoutQuery);
			}
			const correctLayoutSearchPromise = this.getItemsFromCacheByQuery(originalLayoutQuery);
			return Promise.all([correctLayoutSearchPromise, wrongLayoutSearchPromise]).then(result => {
				return new Map([...result[0], ...result[1]]);
			}).catch(error => {
				console.info(error)
				// console.error('Unknown exception', error);
				return new Map();
			});
		}
		searchInRecentList(originalLayoutQuery) {
			let wrongLayoutSearchPromise = Promise.resolve([]);
			if (this.needLayoutChange(originalLayoutQuery)) {
				const wrongLayoutQuery = this.changeLayout(originalLayoutQuery);
				wrongLayoutSearchPromise = this.getItemsFromRecentListByQuery(wrongLayoutQuery);
			}
			const correctLayoutSearchPromise = this.getItemsFromRecentListByQuery(originalLayoutQuery);
			return Promise.all([correctLayoutSearchPromise, wrongLayoutSearchPromise]).then(result => {
				return new Map([...result[0], ...result[1]]);
			});
		}
		getItemsFromRecentListByQuery(query) {
			const queryWords = SearchUtils.getWordsFromString(query);
			return this.recentList.search(queryWords).then(items => {
				return this.#filterResult(items);
			});
		}
		getSearchConfig() {
			return Config.get();
		}
		onItemSelect(event) {
			const {
				selectedItem,
				onlyOpen
			} = event.getData();
			const item = [selectedItem.entityId, selectedItem.id];
			if (!onlyOpen) {
				this.cache.unshiftItem(item);
				this.addItemsToRecentSearchResults(item);
			}
		}
		onOpenNetworkItem(event) {
			const code = event.getData();
			return new Promise((resolve, reject) => {
				this.restClient.callBatch(this.getDataRequestQuery(code), result => resolve(this.handleBatchRequestResult(result)), error => reject(error));
			});
		}
		handleBatchRequestResult(result) {
			if (result[RestMethodImopenlinesNetworkJoin] && result[RestMethodImopenlinesNetworkJoin].error()) {
				return {
					error: result[RestMethodImopenlinesNetworkJoin].error().ex.error_description
				};
			}
			if (result[im_oldChatEmbedding_const.RestMethod.imUserGet] && result[im_oldChatEmbedding_const.RestMethod.imUserGet].error()) {
				return {
					error: result[im_oldChatEmbedding_const.RestMethod.imUserGet].error().ex.error_description
				};
			}
			const user = result[im_oldChatEmbedding_const.RestMethod.imUserGet].data();
			this.store.dispatch('users/set', [user]);
			const dialogue = this.prepareChatForAdditionalUser(user);
			this.store.dispatch('dialogues/set', [dialogue]);
			return user;
		}
		prepareChatForAdditionalUser(user) {
			return {
				dialogId: user.id,
				avatar: user.avatar,
				color: user.color,
				name: user.name,
				type: im_oldChatEmbedding_const.DialogType.user
			};
		}
		getDataRequestQuery(code) {
			const query = {
				[RestMethodImopenlinesNetworkJoin]: [RestMethodImopenlinesNetworkJoin, {
					code: code
				}]
			};
			query[im_oldChatEmbedding_const.RestMethod.imUserGet] = [im_oldChatEmbedding_const.RestMethod.imUserGet, {
				id: `$result[${RestMethodImopenlinesNetworkJoin}]`
			}];
			return query;
		}
		getItemsFromCacheByQuery(query) {
			const queryWords = SearchUtils.getWordsFromString(query);
			return this.cache.search(queryWords).then(cacheItems => {
				const items = SearchUtils.createItemMap(cacheItems);
				const filteredItems = this.#filterResult(items);
				return this.updateModels(filteredItems).then(() => filteredItems);
			});
		}
		getSortedItems(items, originalLayoutQuery) {
			let sortedItems = this.sortItemsBySearchField(items, originalLayoutQuery);
			sortedItems = this.sortItemsByEntityIdAndContextSort(sortedItems);
			return sortedItems;
		}
		sortItemsBySearchField(items, originalLayoutQuery) {
			let queryWords = SearchUtils.getWordsFromString(originalLayoutQuery);
			if (this.needLayoutChange(originalLayoutQuery)) {
				const wrongLayoutQueryWords = SearchUtils.getWordsFromString(this.changeLayout(originalLayoutQuery));
				queryWords = [...queryWords, ...wrongLayoutQueryWords];
			}
			const uniqueWords = [...new Set(queryWords)];
			const searchFieldsWeight = {
				title: 10_000,
				name: 1000,
				lastName: 100,
				position: 1
			};
			items.forEach(item => {
				uniqueWords.forEach(word => {
					if (item.getTitle().toLowerCase().startsWith(word)) {
						item.addCustomSort(searchFieldsWeight.title);
					} else if (item.getName()?.toLowerCase().startsWith(word)) {
						item.addCustomSort(searchFieldsWeight.name);
					} else if (item.getLastName()?.toLowerCase().startsWith(word)) {
						item.addCustomSort(searchFieldsWeight.lastName);
					} else if (item.getPosition()?.toLowerCase().startsWith(word)) {
						item.addCustomSort(searchFieldsWeight.position);
					}
				});
			});
			return new Map([...items.entries()].sort((firstItem, secondItem) => {
				const [, firstItemValue] = firstItem;
				const [, secondItemValue] = secondItem;
				return secondItemValue.getCustomSort() - firstItemValue.getCustomSort();
			}));
		}
		sortItemsByEntityIdAndContextSort(items) {
			const entityWeight = {
				'user': 100,
				'im-chat': 80,
				'im-chat-user': 80,
				'im-bot': 70,
				'department': 60,
				'extranet': 10
			};
			return new Map([...items.entries()].sort((firstItem, secondItem) => {
				const [, firstItemValue] = firstItem;
				const [, secondItemValue] = secondItem;
				const secondItemEntityId = secondItemValue.isExtranet() ? 'extranet' : secondItemValue.getEntityId();
				const firstItemEntityId = firstItemValue.isExtranet() ? 'extranet' : firstItemValue.getEntityId();
				if (entityWeight[secondItemEntityId] < entityWeight[firstItemEntityId]) {
					return -1;
				} else if (entityWeight[secondItemEntityId] > entityWeight[firstItemEntityId]) {
					return 1;
				} else {
					return secondItemValue.getContextSort() - firstItemValue.getContextSort();
				}
			}));
		}
		loadRecentFromServer() {
			const config = {
				json: this.getSearchConfig()
			};
			return new Promise((resolve, reject) => {
				main_core.ajax.runAction('ui.entityselector.load', config).then(response => {
					im_oldChatEmbedding_lib_logger.Logger.warn(`Im.Search: Recent search request result`, response);
					this.cache.save(response.data.dialog);
					resolve(response.data.dialog);
				}).catch(error => reject(error));
			});
		}
		loadDepartmentUsersFromServer(parentItem) {
			const config = {
				json: {
					...this.getSearchConfig(),
					parentItem
				}
			};
			return new Promise((resolve, reject) => {
				main_core.ajax.runAction('ui.entityselector.getChildren', config).then(response => {
					im_oldChatEmbedding_lib_logger.Logger.warn('Im.Search: load department users result', response);
					this.cache.save(response.data.dialog);
					resolve(response.data.dialog.items);
				}).catch(error => reject(error));
			});
		}
		searchRequest(query, requestConfig) {
			const config = {
				json: this.getSearchConfig()
			};
			config.json.searchQuery = {
				'queryWords': SearchUtils.getWordsFromString(query.trim()),
				'query': query.trim()
			};
			return new Promise((resolve, reject) => {
				main_core.ajax.runAction('ui.entityselector.doSearch', config).then(response => {
					im_oldChatEmbedding_lib_logger.Logger.warn(`Im.Search: Search request result`, response);
					this.cache.save(response.data.dialog);
					resolve(response.data.dialog.items);
				}).catch(error => reject(error));
			});
		}
		searchOnNetworkRequest(query) {
			const config = {
				json: this.getSearchConfig()
			};
			config.json.searchQuery = {
				'queryWords': SearchUtils.getWordsFromString(query.trim()),
				'query': query.trim()
			};
			return new Promise((resolve, reject) => {
				main_core.ajax.runAction('ui.entityselector.doSearch', config).then(response => {
					im_oldChatEmbedding_lib_logger.Logger.warn(`Im.Search: Network Search request result`, response);
					resolve(response.data.dialog.items);
				}).catch(error => reject(error));
			});
		}
		addItemsToRecentSearchResults(recentItem) {
			const [entityId, id] = recentItem;
			const recentItems = [{
				id,
				entityId
			}];
			const config = {
				json: {
					...this.getSearchConfig(),
					recentItems
				}
			};
			main_core.ajax.runAction('ui.entityselector.saveRecentItems', config);
		}
		updateModels(items, set = false) {
			const {users, dialogues} = this.prepareDataForModels(items);
			const usersActionName = set ? 'users/set' : 'users/add';
			const dialoguesActionName = set ? 'dialogues/set' : 'dialogues/add';
			const usersPromise = this.store.dispatch(usersActionName, users);
			const dialoguesPromise = this.store.dispatch(dialoguesActionName, dialogues);

			return Promise.all([usersPromise, dialoguesPromise]);
		}
		prepareDataForModels(items) {
			const result = {
				users: [],
				dialogues: []
			};
			items.forEach(item => {
				if (!item.getCustomData()) {
					return;
				}

				// user
				if (item.isUser()) {
					const preparedUser = SearchUtils.convertKeysToLowerCase(item.getUserCustomData());
					result.users.push(preparedUser);
					result.dialogues.push({
						avatar: preparedUser.avatar,
						color: preparedUser.color,
						name: preparedUser.name,
						type: im_oldChatEmbedding_const.DialogType.user,
						dialogId: item.getId()
					});
				}

				// chat
				if (item.isChat() && !item.isOpeLinesType()) {
					const chat = SearchUtils.convertKeysToLowerCase(item.getChatCustomData());
					result.dialogues.push({
						...chat,
						dialogId: `chat${chat.id}`
					});
				}
			});
			return result;
		}
		getItemsFromRecentItems(recentItems, items) {
			const filledRecentItems = new Map();
			recentItems.forEach(recentItem => {
				const itemFromMap = items.get(recentItem.cacheId);
				if (itemFromMap && !itemFromMap.isOpeLinesType()) {
					filledRecentItems.set(itemFromMap.getEntityFullId(), itemFromMap);
				}
			});
			return filledRecentItems;
		}
		allocateSearchResults(items, originalLayoutQuery) {
			const usersAndChats = new Map();
			const chatUsers = new Map();
			const departments = new Map();
			const openLines = new Map();
			const network = new Map();
			items.forEach(item => {
				switch (item.getEntityId()) {
					case EntityIdTypes.chatUser:
						{
							chatUsers.set(item.getEntityFullId(), item);
							break;
						}
					case EntityIdTypes.department:
						{
							departments.set(item.getEntityFullId(), item);
							break;
						}
					case EntityIdTypes.network:
						{
							network.set(item.getEntityFullId(), item);
							break;
						}
					default:
						{
							if (item.isOpeLinesType()) {
								openLines.set(item.getEntityFullId(), item);
							} else {
								usersAndChats.set(item.getEntityFullId(), item);
							}
						}
				}
			});
			return {
				usersAndChats: this.getSortedItems(usersAndChats, originalLayoutQuery),
				chatUsers: chatUsers,
				departments: departments,
				openLines: openLines,
				network: network
			};
		}
		isRussianInterface() {
			return this.store.state.application.common.languageId === 'ru';
		}
		changeLayout(query) {
			if (this.isRussianInterface() && BX.correctText) {
				// eslint-disable-next-line bitrix-rules/no-bx
				return BX.correctText(query, {
					replace_way: 'AUTO'
				});
			}
			return query;
		}
		needLayoutChange(originalLayoutQuery) {
			const wrongLayoutQuery = this.changeLayout(originalLayoutQuery);
			const isIdenticalQuery = wrongLayoutQuery === originalLayoutQuery;
			return this.isRussianInterface() && !isIdenticalQuery;
		}
		#filterResult(items) {
			if (this.applicationLayout === im_oldChatEmbedding_const.ApplicationLayout.full) {
				return items;
			}
			const filteredItems = [...items].filter(item => {
				const [, value] = item;
				return this.#isItemAllowedInLinesSearch(value);
			});
			return new Map(filteredItems);
		}
		#isItemAllowedInLinesSearch(item) {
			return item.isUser() || item.isLines() || item.isChat();
		}
	}

	/**
	* @bitrixEvents EventType.search.openContextMenu
	* @bitrixEvents EventType.dialog.errors.accessDenied
	* @bitrixEvents EventType.search.selectItem
	* @bitrixEvents EventType.recent.updateSearch
	*/
	const Search = {
		components: {
			RecentUsersCarousel,
			SearchResultSection,
			LoadingState: im_oldChatEmbedding_component_elements.RecentLoadingState,
			SearchResultItem
		},
		props: {
			searchQuery: {
				type: String,
				required: true
			},
			searchMode: {
				type: Boolean,
				required: true
			}
		},
		data: function () {
			return {
				isRecentLoading: false,
				isLocalLoading: false,
				isServerLoading: false,
				isNetworkLoading: false,
				currentServerQueries: 0,
				isNetworkButtonClicked: false,
				isNetworkAvailable: false,
				isNetworkSearchEnabled: true,
				result: {
					recent: new Map(),
					usersAndChats: new Map(),
					chatUsers: new Map(),
					departments: new Map(),
					openLines: new Map(),
					network: new Map()
				}
			};
		},
		computed: {
			isEmptyState() {
				if (this.isServerLoading || this.isLocalLoading || this.isNetworkLoading) {
					return false;
				}

				const openLinesLayout = this.applicationLayout === im_oldChatEmbedding_const.ApplicationLayout.lines;

				if (!openLinesLayout && this.isNetworkSearchEnabled && !this.isNetworkButtonClicked && this.isServerSearch) {
					return false;
				}

				return this.result.usersAndChats.size === 0 && this.result.departments.size === 0 && this.result.chatUsers.size === 0 && this.result.openLines.size === 0 && this.result.network.size === 0;
			},
			isLoadingState() {
				return this.isServerLoading || this.isRecentLoading;
			},
			isServerSearch() {
				return this.searchQuery.trim().length >= this.minTokenSize;
			},
			needToShowNetworkSection() {
				return !this.isNetworkButtonClicked || this.result.network.size > 0;
			},
			showSearchResult() {
				return this.searchQuery.trim().length > 0;
			},
			isNetworkSearchCode() {
				return !!(this.searchQuery.length === 32 && /[\da-f]{32}/.test(this.searchQuery));
			},
			isNetworkAvailableForSearch() {
				if (this.applicationLayout !== im_oldChatEmbedding_const.ApplicationLayout.full) {
					return false;
				}
				if (!this.isNetworkAvailable) {
					return false;
				}
				return this.isNetworkSearchEnabled || this.isNetworkSearchCode;
			},
			itemComponent: () => SearchResultItem,
		},
		watch: {
			searchQuery(newValue, oldValue) {
				const newQuery = newValue.trim();
				const previousQuery = oldValue.trim();
				if (newQuery === previousQuery) {
					return;
				}

				this.startSearch(newQuery);
			},
			searchMode(newValue, oldValue) {
				if (newValue === false && oldValue === true) // search switch off
				{
					this.isNetworkButtonClicked = false;
				} else if (newValue === true && oldValue === false) // search switch on
				{
					if (this.result.recent.size > 0) {
						return;
					}

					this.isRecentLoading = true;
				}

				this.searchService.loadRecentSearchFromServer().then(recentItems => {
					this.result.recent = recentItems;
					this.isRecentLoading = false;
				});
			}
		},
		created() {
			this.initSettings();
			this.contextMenuManager = new SearchContextMenu(this.$Bitrix);
			const cache = new SearchCache(this.getCurrentUserId());
			const recentList = new SearchRecentList(this.$Bitrix);
			this.searchService = SearchService.getInstance(this.$Bitrix, cache, recentList);
			this.searchOnServerDelayed = main_core.Runtime.debounce(this.searchOnServer, 1500, this);
			this.applicationLayout = this.$Bitrix.Application.get().params.layout;
			main_core_events.EventEmitter.subscribe(im_oldChatEmbedding_const.EventType.search.openContextMenu, this.onOpenContextMenu);
			main_core_events.EventEmitter.subscribe(im_oldChatEmbedding_const.EventType.dialog.errors.accessDenied, this.onDelete);
			main_core_events.EventEmitter.subscribe(im_oldChatEmbedding_const.EventType.search.selectItem, this.onSelectItem);
			main_core_events.EventEmitter.subscribe(im_oldChatEmbedding_const.EventType.recent.updateSearch, this.onPressEnterKey);

			this.loadInitialRecentFromCache();
		},
		beforeUnmount() {
			this.searchService.destroy();
			this.contextMenuManager.destroy();
			main_core_events.EventEmitter.unsubscribe(im_oldChatEmbedding_const.EventType.search.openContextMenu, this.onOpenContextMenu);
			main_core_events.EventEmitter.unsubscribe(im_oldChatEmbedding_const.EventType.dialog.errors.accessDenied, this.onDelete);
			main_core_events.EventEmitter.unsubscribe(im_oldChatEmbedding_const.EventType.search.selectItem, this.onSelectItem);
			main_core_events.EventEmitter.unsubscribe(im_oldChatEmbedding_const.EventType.recent.updateSearch, this.onPressEnterKey);
		},
		methods: {
			loadInitialRecentFromCache() {
				// we don't need an extra request to get recent items while messenger initialization
				this.searchService.loadRecentSearchFromCache().then(recentItems => {
					this.result.recent = recentItems;
				});
			},
			initSettings() {
				const settings = main_core.Extension.getSettings('langmess.old-chat-embedding.component.search');
				const defaultMinTokenSize = 3;
				this.minTokenSize = settings.get('minTokenSize', defaultMinTokenSize);
				this.isNetworkAvailable = settings.get('isNetworkAvailable', false);
				this.isNetworkSearchEnabled = settings.get('isNetworkSearchEnabled', false);
				this.isDepartmentsAvailable = settings.get('isDepartmentsAvailable', false);
			},
			startSearch(searchQuery) {
				if (searchQuery.length > 0 && searchQuery.length < this.minTokenSize) {
					this.isLocalLoading = true;
					const queryBeforeRequest = searchQuery;
					this.searchService.searchLocal(searchQuery).then(localSearchResult => {
						if (queryBeforeRequest !== this.searchQuery.trim()) {
							return;
						}
						this.result.usersAndChats = localSearchResult;
						this.isLocalLoading = false;
					});
				} else if (searchQuery.length >= this.minTokenSize) {
					this.isServerLoading = true;
					const queryBeforeRequest = searchQuery;
					this.searchService.searchLocal(searchQuery).then(localSearchResult => {
						if (queryBeforeRequest !== this.searchQuery.trim()) {
							return;
						}
						this.result.usersAndChats = localSearchResult;
					}).then(() => this.searchOnServerDelayed(searchQuery));
				} else {
					this.cleanSearchResult();
				}
			},
			cleanSearchResult() {
				this.result.usersAndChats = new Map();
				this.result.departments = new Map();
				this.result.chatUsers = new Map();
				this.result.network = new Map();
				this.result.openLines = new Map();
			},
			searchOnServer(query) {
				this.currentServerQueries++;
				this.isNetworkLoading = this.isNetworkButtonClicked;
				const config = {
					lines: true,
					// users: true,
					// chats: true
				};
				const queryBeforeRequest = query;
				this.searchService.searchOnServer(query, config).then(searchResultFromServer => {
					if (queryBeforeRequest !== this.searchQuery.trim()) {
						this.stopLoader();
						return;
					}
					this.result.usersAndChats = this.mergeResults(this.result.usersAndChats, searchResultFromServer.usersAndChats);
					this.result.departments = searchResultFromServer.departments;
					this.result.chatUsers = searchResultFromServer.chatUsers;
					this.result.openLines = searchResultFromServer.openLines;
					this.result.network = searchResultFromServer.network;
				}).catch(error => {
					console.info(error)
					// console.error(error);
				}).finally(() => {
					this.currentServerQueries--;
					this.stopLoader();
				});
			},
			stopLoader() {
				if (this.currentServerQueries > 0) {
					return;
				}
				this.isNetworkLoading = false;
				this.isServerLoading = false;
				this.isLocalLoading = false;
			},
			searchOnNetwork(query) {
				this.isNetworkLoading = true;
				const queryBeforeRequest = query;
				this.searchService.searchOnNetwork(query).then(searchResultFromServer => {
					if (queryBeforeRequest !== this.searchQuery) {
						this.isNetworkLoading = false;
						return;
					}
					this.result.network = searchResultFromServer;
					this.isNetworkButtonClicked = true;
					this.isNetworkLoading = false;
				});
			},
			mergeResults(originalItems, newItems) {
				const mergedMap = new Map(originalItems.entries());
				newItems.forEach((newItemValue, newItemKey) => {
					if (!mergedMap.has(newItemKey)) {
						mergedMap.set(newItemKey, newItemValue);
					}
				});
				return mergedMap;
			},
			onOpenContextMenu({
				data: eventData
			}) {
				if (eventData.event.altKey && eventData.event.shiftKey) {
					return;
				}
				this.contextMenuManager.openMenu(eventData.item, eventData.event.currentTarget);
			},
			onDelete({
				data: eventData
			}) {
				const {dialogId} = eventData;

				this.result.recent.delete(dialogId);
				this.result.usersAndChats.delete(dialogId);
				this.result.chatUsers.delete(dialogId);
			},
			onScroll() {
				this.contextMenuManager.destroy();
			},
			onClickLoadNetworkResult() {
				this.searchOnNetwork(this.searchQuery);
			},
			onSelectItem(event) {
				const {selectedItem, nativeEvent} = event.getData();
				let data = {};
				let store = im_oldChatEmbedding_application_core.Core.getStore();

				if(selectedItem.entityId == 'im-chat') {
					data = store.getters['dialogues/get'](selectedItem.dialogId, true)
				} else {
					data = store.getters['users/get'](selectedItem.dialogId, true)
				}

				data.id = selectedItem.dialogId

				main_core_events.EventEmitter.emit(im_oldChatEmbedding_const.EventType.dialog.open, {
					// dialogId: selectedItem.dialogId,
					...data,
					// chatType: selectedItem.entityId == 'im-chat' ? "chat" : "user",
					// chat: store.getters['dialogues/get'](selectedItem.dialogId, true),
					user: store.getters['users/get'](selectedItem.dialogId, true)
				});

				if (!nativeEvent.altKey) {
					BX.MessengerProxy.clearSearchInput();
				}
			},
			onPressEnterKey(event) {
				if (event.data.keyCode !== 13) // enter
				{
					return;
				}

				const firstItem = this.getFirstItemFromSearchResults();

				if (!firstItem) {
					return;
				}
				const selectedItem = {
					id: firstItem.getId(),
					entityId: firstItem.getEntityId(),
					dialogId: firstItem.getDialogId()
				};
				main_core_events.EventEmitter.emit(im_oldChatEmbedding_const.EventType.search.selectItem, {
					selectedItem: selectedItem,
					onlyOpen: firstItem.isOpeLinesType(),
					nativeEvent: {}
				});
			},
			getFirstItemFromSearchResults() {
				if (!this.showSearchResult && this.result.recent.size > 0) {
					return SearchUtils.getFirstItemFromMap(this.result.recent);
				}
				if (this.result.usersAndChats.size > 0) {
					return SearchUtils.getFirstItemFromMap(this.result.usersAndChats);
				}
				if (this.result.chatUsers.size > 0) {
					return SearchUtils.getFirstItemFromMap(this.result.chatUsers);
				}
				if (this.result.openLines.size > 0) {
					return SearchUtils.getFirstItemFromMap(this.result.openLines);
				}
				return null;
			},
			getCurrentUserId() {
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				return store.state.application.common.userId;
			}
		},
		template: `
		<div class="bx-messenger-recent" @scroll="onScroll">
			<div class="bx-messenger-recent-list w-100 h-100">
				<template v-if="!showSearchResult">
					<RecentUsersCarousel />
					<SearchResultSection
						:component="itemComponent"
						:items="result.recent" 
						:title="$Bitrix.Loc.getMessage('IM_SEARCH_SECTION_RECENT')" 
						:showMoreButton="false" 
					/>
				</template>
				<template v-if="showSearchResult">
					<SearchResultSection 
						v-if="result.usersAndChats.size > 0"
						:component="itemComponent"
						:items="result.usersAndChats"
						:title="$Bitrix.Loc.getMessage('IM_SEARCH_SECTION_USERS_AND_CHATS')"
						:min-items:="20"
						:max-items="50"
					/>
					<template v-if="!isLoadingState && isServerSearch">
						<SearchResultSection
							v-if="result.chatUsers.size > 0"
							:component="itemComponent"
							:items="result.chatUsers"
							:title="$Bitrix.Loc.getMessage('IM_SEARCH_SECTION_CHAT_USERS')"
							:min-items:="5"
							:max-items="20"
						/>
						<template v-if="isNetworkAvailableForSearch">
							<template v-if="!isNetworkButtonClicked">
								<div 
									v-if="!isNetworkLoading"
									@click="onClickLoadNetworkResult"
									class="bx-im-search-network-button"
								>
									{{$Bitrix.Loc.getMessage('IM_SEARCH_SECTION_NETWORK_BUTTON_MSGVER_1')}}
								</div>
								<div v-else class="bx-search-network-loader-wrapper">
									<div class="bx-search-loader bx-search-loader-large-size"></div>
								</div>
							</template>
						</template>
					</template>
					<div v-if="isEmptyState" class="bx-im-search-not-found-wrapper">
						<div class="bx-im-search-not-found-icon"></div>
						<div class="bx-im-search-not-found-title">{{ $Bitrix.Loc.getMessage('IM_SEARCH_RESULT_NOT_FOUND') }}</div>
						<div class="bx-im-search-not-found-title">
							{{ $Bitrix.Loc.getMessage('IM_SEARCH_RESULT_NOT_FOUND_DESCRIPTION') }}
						</div>
					</div>
				</template>
				<LoadingState v-if="isLoadingState" />
			</div>
		</div>
	`
	};

	exports.Search = Search;

})(
	this.BX.Messenger.Embedding.ComponentLegacy = this.BX.Messenger.Embedding.ComponentLegacy || {}, window, BX, BX.Event,
	BX.Messenger.Embedding.Const, BX.Messenger.Embedding.ComponentLegacy, BX.Messenger.Embedding.LibLegacy, BX.Messenger.Embedding.Lib,
	BX,
	BX.Messenger.Embedding.Lib,
	BX.DexieExport,
	BX.Messenger.Embedding.Application
);
//# sourceMappingURL=search.bundle.js.map
