document.addEventListener("alpine:init", () => {
    //chat
    console.log("chat");
    Alpine.data("chat", () => ({
        chatMenu: false,
        chatUser: false,
        loginUser: {
            id: 0,
            name: "Jon Doe",
            path: "avatar-1.png",
            designation: "Software Developer",
        },
        contactList: [
            {
                userId: 1,
                name: "Nia Hillyer",
                path: "avatar-2.png",
                time: "2:09 PM",
                preview: "How do you do?",
                messages: [
                    {
                        fromUserId: 0,
                        toUserId: 1,
                        text: "Hi, I am back from vacation",
                    },
                    {
                        fromUserId: 0,
                        toUserId: 1,
                        text: "How are you?",
                    },
                    {
                        fromUserId: 1,
                        toUserId: 0,
                        text: "Welcom Back",
                    },
                    {
                        fromUserId: 1,
                        toUserId: 0,
                        text: "I am all well",
                    },
                    {
                        fromUserId: 0,
                        toUserId: 1,
                        text: "Coffee?",
                    },
                ],
                active: true,
            },
            {
                userId: 2,
                name: "Sean Freeman",
                path: "avatar-3.png",
                time: "12:09 PM",
                preview: "I was wondering...",
                messages: [
                    {
                        fromUserId: 0,
                        toUserId: 2,
                        text: "Hello",
                    },
                    {
                        fromUserId: 0,
                        toUserId: 2,
                        text: "It's me",
                    },
                    {
                        fromUserId: 0,
                        toUserId: 2,
                        text: "I have a question regarding project.",
                    },
                ],
                active: false,
            },
            {
                userId: 3,
                name: "Alma Clarke",
                path: "avatar-4.png",
                time: "1:44 PM",
                preview: "I’ve forgotten how it felt before",
                messages: [
                    {
                        fromUserId: 0,
                        toUserId: 3,
                        text: "Hey Buddy.",
                    },
                    {
                        fromUserId: 0,
                        toUserId: 3,
                        text: "What's up",
                    },
                    {
                        fromUserId: 3,
                        toUserId: 0,
                        text: "I am sick",
                    },
                    {
                        fromUserId: 0,
                        toUserId: 3,
                        text: "Not comming to office today.",
                    },
                ],
                active: true,
            },
            {
                userId: 4,
                name: "Alan Green",
                path: "avatar-5.png",
                time: "2:06 PM",
                preview: "But we’re probably gonna need a new carpet.",
                messages: [
                    {
                        fromUserId: 0,
                        toUserId: 4,
                        text: "Hi, collect your check",
                    },
                    {
                        fromUserId: 4,
                        toUserId: 0,
                        text: "Ok, I will be there in 10 mins",
                    },
                ],
                active: true,
            },
            {
                userId: 5,
                name: "Shaun Park",
                path: "avatar-6.png",
                time: "2:05 PM",
                preview: "It’s not that bad...",
                messages: [
                    {
                        fromUserId: 0,
                        toUserId: 3,
                        text: "Hi, I am back from vacation",
                    },
                    {
                        fromUserId: 0,
                        toUserId: 3,
                        text: "How are you?",
                    },
                    {
                        fromUserId: 0,
                        toUserId: 5,
                        text: "Welcom Back",
                    },
                    {
                        fromUserId: 0,
                        toUserId: 5,
                        text: "I am all well",
                    },
                    {
                        fromUserId: 5,
                        toUserId: 0,
                        text: "Coffee?",
                    },
                ],
                active: false,
            },
            {
                userId: 6,
                name: "Roxanne",
                path: "avatar-7.png",
                time: "2:00 PM",
                preview: "Wasup for the third time like is you bling bitch",
                messages: [
                    {
                        fromUserId: 0,
                        toUserId: 6,
                        text: "Hi",
                    },
                    {
                        fromUserId: 0,
                        toUserId: 6,
                        text: "Uploaded files to server.",
                    },
                ],
                active: false,
            },
            {
                userId: 7,
                name: "Ernest Reeves",
                path: "avatar-8.png",
                time: "2:09 PM",
                preview: "Wasup for the third time like is you bling bitch",
                messages: [],
                active: true,
            },
            {
                userId: 8,
                name: "Laurie Fox",
                path: "avatar-8.png",
                time: "12:09 PM",
                preview: "Wasup for the third time like is you bling bitch",
                messages: [],
                active: true,
            },
            {
                userId: 9,
                name: "Xavier",
                path: "avatar-9.png",
                time: "4:09 PM",
                preview: "Wasup for the third time like is you bling bitch",
                messages: [],
                active: false,
            },
            {
                userId: 10,
                name: "Susan Phillips",
                path: "avatar-10.png",
                time: "9:00 PM",
                preview: "Wasup for the third time like is you bling bitch",
                messages: [],
                active: true,
            },
            {
                userId: 11,
                name: "Dale Butler",
                path: "avatar-12.png",
                time: "5:09 PM",
                preview: "Wasup for the third time like is you bling bitch",
                messages: [],
                active: false,
            },
            {
                userId: 12,
                name: "Grace Roberts",
                path: "avatar-13.png",
                time: "8:01 PM",
                preview: "Wasup for the third time like is you bling bitch",
                messages: [],
                active: true,
            },
        ],
        searchUser: "",
        textMessage: "",
        selectedUser: "",

        get searchUsers() {
            setTimeout(() => {
                const element = document.querySelector(".chat-users");
                element.scrollTop = 0;
                element.behavior = "smooth";
            });
            return this.contactList.filter((d) => {
                return d.name.toLowerCase().includes(this.searchUser);
            });
        },

        selectUser(user) {
            this.selectedUser = user;
            this.chatUser = true;
            this.scrollToBottom;
            this.chatMenu = false;
        },

        sendMessage() {
            if (this.textMessage.trim()) {
                const user = this.contactList.find(
                    (d) => d.userId === this.selectedUser.userId
                );
                user.messages.push({
                    fromUserId: this.selectedUser.userId,
                    toUserId: 0,
                    text: this.textMessage,
                    time: "Just now",
                });
                this.textMessage = "";
                this.scrollToBottom;
            }
        },

        get scrollToBottom() {
            if (this.chatUser) {
                setTimeout(() => {
                    const element = document.querySelector(
                        ".chat-conversation-box"
                    );
                    element.scrollIntoView({
                        behavior: "smooth",
                        block: "end",
                    });
                });
            }
        },
    }));
});
