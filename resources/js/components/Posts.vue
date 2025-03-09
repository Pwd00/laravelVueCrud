<template>
    <div class="max-w-4xl rounded-md bg-white p-8 shadow-lg mx-auto">
        <h2
            class="text-3xl text-purple-400 font-extrabold mb-3 underline-offset-0 shadow-md p-4 shadow-green-300"
        >
            Lists Of Posts 🚀
        </h2>
        <form @submit.prevent="savePosts()" method="post">
            <div class="mb-4">
                <label
                    for="title"
                    class="block text-gray-700 text-sm font-bold mb-2"
                    >Title</label
                >
                <input
                    type="text"
                    id="title"
                    v-model="form.title"
                    placeholder="Title"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    :class="{ 'border-red-500': errors.title }"
                />
                <p v-if="errors.title" class="text-red-500 text-xs italic mt-1">
                    {{ errors.title[0] }}
                </p>
            </div>
            <div class="mb-4">
                <label
                    for="content"
                    class="block text-gray-700 text-sm font-bold mb-2"
                    >Content</label
                >
                <textarea
                    id="content"
                    rows="4"
                    v-model="form.content"
                    placeholder="Content"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    :class="{ 'border-red-500': errors.content }"
                ></textarea>
                <p
                    v-if="errors.content"
                    class="text-red-500 text-xs italic mt-1"
                >
                    {{ errors.content[0] }}
                </p>
            </div>
            <button
                type="submit"
                class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            >
                {{ editMode ? "Update" : "Save" }}
            </button>
        </form>
    </div>
    <div class="mt-8">
        <h3 class="text-2xl font-bold mb-4">Recent Posts</h3>

        <div
            v-for="post in posts"
            :key="post.id"
            class="bg-white p-4 shadow-md mb-4"
        >
            <h2 class="text-xl font-bold mb-2">{{ post.title }}</h2>
            <p class="text-gray-700 mb-4">{{ post.content }}</p>
            <div class="flex space-x-2">
                <button
                    @click="editPost(post)"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Edit
                </button>
                <button
                    @click="deletePost(post.id)"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>
    <div class="mt-4 flex justify-center gap-2">
        <button
            v-for="(link, index) in links"
            :key="index"
            @click="fetchPosts(link.url)"
            :disabled="!link.url"
            class="px-3 py-1 rounded"
            :class="{
                'bg-purple-500 text-white': link.active,
                'bg-gray-200': !link.active,
                'opacity-50 cursor-not-allowed': !link.url,
            }"
            v-html="link.label"
        ></button>
    </div>
</template>
<script>
import axios from "axios";
export default {
    data() {
        return {
            posts: {},
            links: {},
            errors: {},
            form: {
                title: "",
                content: "",
            },
            editMode: false,
            editid: null,
        };
    },
    methods: {
        async fetchPosts(url = "api/posts") {
            const { data } = await axios.get(url);
            //console.log(data.posts.data);
            this.posts = data.posts.data;
            this.links = data.posts.links;
            console.log(this.links);
        },
        async savePosts() {
            this.errors = {};
            try {
                if (this.editmode) {
                    const response = await axios.put("api/posts", this.form);
                    this.editmode = false;
                    this.editid = null;
                } else {
                    const response = await axios.post("api/posts", this.form);
                    if (response.status === 201) {
                        this.form.title = "";
                        this.form.content = "";
                        this.fetchPosts();
                    }
                }
            } catch (error) {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            }
        },
        async deletePost(id) {
            await axios.delete(`api/posts/${id}`);
            this.fetchPosts();
        },
        editPost(post) {
            this.form = { ...post };
            this.editid = post.id;
            this.editMode = true;
        },
    },
    mounted() {
        this.fetchPosts();
    },
};
</script>
