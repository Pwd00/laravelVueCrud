<template>
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <Header />
        <!-- Main Content with Sidebar -->
        <div class="flex flex-1">
            <!-- Sidebar -->
            <Sidebar />
            <!-- Main Content -->
            <main class="flex-1 p-6 bg-gray-300">
                <Posts />
            </main>
        </div>
    </div>
</template>
<script>
import Posts from "./components/Posts.vue";
import Header from "./components/Header.vue";
import Sidebar from "./components/Sidebar.vue";

export default {
    components: {
        Posts,
        Header,
        Sidebar,
    },
};
</script>
