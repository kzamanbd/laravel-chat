export interface User {
    id: number;
    name: string;
    email: string;
    avatar_path: string;
    email_verified_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};

export type Message = {
    id:number;
    user_id: number;
    message: string;
    created_at: string;
};

export type Conversation = {
    id: number;
    username: string;
    from_user: User;
    to_user: User;
    uuid: string;
    last_message: string;
    last_msg_at: string;
    messages: Message[];
    active: boolean;
};

export type Contact = {
    userId: number;
    name: string;
    path: string;
    time: string;
    preview: string;
    messages: Message[];
    active: boolean;
    uuid: String;
};
