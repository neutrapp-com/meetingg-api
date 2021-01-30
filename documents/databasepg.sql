CREATE  TABLE "public".meeting ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	title                varchar(225) DEFAULT 'Meeting'::character varying  ,
	description          text   ,
	"password"           varchar(100)   ,
	video                boolean DEFAULT true  ,
	audio                boolean DEFAULT true  ,
	sharedscreen         boolean DEFAULT true  ,
	locked               boolean DEFAULT false  ,
	locked_at            timestamp   ,
	start_at             timestamp(0) DEFAULT CURRENT_TIMESTAMP  ,
	end_at               timestamp(0)   ,
	created_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP  ,
	updated_at           timestamp(0)   ,
	deleted_at           timestamp(0)   ,
	CONSTRAINT pk_meeting_id PRIMARY KEY ( id )
 );

COMMENT ON COLUMN "public".meeting.video IS 'video allowed';

COMMENT ON COLUMN "public".meeting.audio IS 'Audio allowed';

COMMENT ON COLUMN "public".meeting.locked IS 'Users can enter or not';

CREATE  TABLE "public".discussion ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	meeting_id           uuid   ,
	title                varchar(225)   ,
	avatar               text   ,
	color                char(7)   ,
	favorite             boolean DEFAULT false  ,
	created_at           timestamp DEFAULT current_timestamp  ,
	updated_at           timestamp   ,
	deleted_at           timestamp   ,
	CONSTRAINT pk_chat_id PRIMARY KEY ( id )
 );

COMMENT ON COLUMN "public".discussion.color IS 'A color can contain 6 digits and #. #000000';

COMMENT ON COLUMN "public".discussion.meeting_id IS 'if meeting_id null, it''s not a meeting discussion, it''s a private discussion group';

CREATE  TABLE "public".contact ( 
	user_id              uuid  NOT NULL ,
	target_id            uuid  NOT NULL ,
	status               smallint DEFAULT 0 NOT NULL ,
	starred              jsonb DEFAULT '[]'  ,
	blocked              boolean DEFAULT false NOT NULL ,
	blocked_at           timestamp   ,
	created_at           timestamp DEFAULT current_timestamp NOT NULL ,
	updated_at           timestamp   ,
	deleted_at           timestamp   ,
	accepted_at          timestamp   ,
	CONSTRAINT pk_contact_user_id PRIMARY KEY ( user_id, target_id ),
	CONSTRAINT unq_contact_target_id UNIQUE ( target_id ) 
 );

CREATE  TABLE "public".discussionusers ( 
	user_id              uuid  NOT NULL ,
	invite_id            uuid   ,
	discussion_id        uuid  NOT NULL ,
	permissions          jsonb DEFAULT '[]'  ,
	created_at           timestamp DEFAULT current_timestamp NOT NULL ,
	updated_at           timestamp   ,
	deleted_at           timestamp   ,
	CONSTRAINT pk_discussionusers PRIMARY KEY ( user_id, discussion_id )
 );

CREATE  TABLE "public"."group" ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	user_id              uuid  NOT NULL ,
	title                varchar(225)  NOT NULL ,
	"count"              smallint DEFAULT 0 NOT NULL ,
	created_at           timestamp DEFAULT current_timestamp NOT NULL ,
	updated_at           timestamp DEFAULT current_timestamp NOT NULL ,
	CONSTRAINT pk_groupcontact_id PRIMARY KEY ( id )
 );

CREATE  TABLE "public".groupcontacts ( 
	group_id             uuid  NOT NULL ,
	contact_id           uuid  NOT NULL ,
	created_at           timestamp DEFAULT current_timestamp NOT NULL ,
	deleted_at           timestamp   ,
	CONSTRAINT pk_groupcontacts PRIMARY KEY ( contact_id, group_id )
 );

CREATE  TABLE "public".invite ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	user_id              uuid  NOT NULL ,
	meeting_id           uuid   ,
	"type"               smallint DEFAULT 0 NOT NULL ,
	"limit"              integer DEFAULT 1 NOT NULL ,
	created_at           timestamp DEFAULT current_timestamp  ,
	updated_at           timestamp   ,
	expirated_at         timestamp  NOT NULL ,
	CONSTRAINT pk_meetinginvite_id PRIMARY KEY ( id )
 );

CREATE  TABLE "public".meetingusers ( 
	meeting_id           uuid  NOT NULL ,
	user_id              uuid  NOT NULL ,
	invite_id            uuid   ,
	permissions          jsonb DEFAULT '[]'::jsonb  ,
	status               smallint DEFAULT 0  ,
	created_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP  ,
	updated_at           timestamp(0)   ,
	deleted_at           timestamp(0)   ,
	CONSTRAINT pk_meetingusers PRIMARY KEY ( user_id, meeting_id )
 );

CREATE  TABLE "public".message ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	user_id              uuid  NOT NULL ,
	discussion_id        uuid  NOT NULL ,
	content              text   ,
	"file"               text   ,
	meta_file            jsonb   ,
	starred              smallint DEFAULT 0  ,
	status               smallint DEFAULT 0  ,
	created_at           timestamp DEFAULT current_timestamp NOT NULL ,
	updated_at           timestamp   ,
	deleted_at           timestamp   ,
	CONSTRAINT pk_message_id PRIMARY KEY ( id ),
	CONSTRAINT unq_message_discussion_id UNIQUE ( discussion_id ) 
 );

CREATE  TABLE "public".notification ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	user_id              uuid  NOT NULL ,
	sender_id            uuid   ,
	meeting_id           uuid   ,
	discussion_id        uuid   ,
	title                varchar(225)   ,
	content              varchar(500)   ,
	status               smallint DEFAULT 0 NOT NULL ,
	created_at           timestamp DEFAULT current_timestamp NOT NULL ,
	CONSTRAINT pk_notification_id PRIMARY KEY ( id )
 );

CREATE  TABLE "public"."user" ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	invite_id            uuid   ,
	firstname            varchar(225)   ,
	lastname             varchar(225)   ,
	email                varchar(225)  NOT NULL ,
	"password"           text  NOT NULL ,
	avatar               text   ,
	phone                varchar(25)   ,
	fax                  varchar(25)   ,
	address              varchar(225)   ,
	city                 varchar(100)   ,
	country              varchar(100)   ,
	status               smallint DEFAULT 0  ,
	created_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP NOT NULL ,
	updated_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP  ,
	CONSTRAINT pk_user_id PRIMARY KEY ( id )
 );

CREATE OR REPLACE FUNCTION public.uuid_generate_v1()
 RETURNS uuid
 LANGUAGE c
 PARALLEL SAFE STRICT
AS '$libdir/uuid-ossp', $function$uuid_generate_v1$function$
;

CREATE OR REPLACE FUNCTION public.uuid_generate_v1mc()
 RETURNS uuid
 LANGUAGE c
 PARALLEL SAFE STRICT
AS '$libdir/uuid-ossp', $function$uuid_generate_v1mc$function$
;

CREATE OR REPLACE FUNCTION public.uuid_generate_v3(namespace uuid, name text)
 RETURNS uuid
 LANGUAGE c
 IMMUTABLE PARALLEL SAFE STRICT
AS '$libdir/uuid-ossp', $function$uuid_generate_v3$function$
;

CREATE OR REPLACE FUNCTION public.uuid_generate_v4()
 RETURNS uuid
 LANGUAGE c
 PARALLEL SAFE STRICT
AS '$libdir/uuid-ossp', $function$uuid_generate_v4$function$
;

CREATE OR REPLACE FUNCTION public.uuid_generate_v5(namespace uuid, name text)
 RETURNS uuid
 LANGUAGE c
 IMMUTABLE PARALLEL SAFE STRICT
AS '$libdir/uuid-ossp', $function$uuid_generate_v5$function$
;

CREATE OR REPLACE FUNCTION public.uuid_nil()
 RETURNS uuid
 LANGUAGE c
 IMMUTABLE PARALLEL SAFE STRICT
AS '$libdir/uuid-ossp', $function$uuid_nil$function$
;

CREATE OR REPLACE FUNCTION public.uuid_ns_dns()
 RETURNS uuid
 LANGUAGE c
 IMMUTABLE PARALLEL SAFE STRICT
AS '$libdir/uuid-ossp', $function$uuid_ns_dns$function$
;

CREATE OR REPLACE FUNCTION public.uuid_ns_oid()
 RETURNS uuid
 LANGUAGE c
 IMMUTABLE PARALLEL SAFE STRICT
AS '$libdir/uuid-ossp', $function$uuid_ns_oid$function$
;

CREATE OR REPLACE FUNCTION public.uuid_ns_url()
 RETURNS uuid
 LANGUAGE c
 IMMUTABLE PARALLEL SAFE STRICT
AS '$libdir/uuid-ossp', $function$uuid_ns_url$function$
;

CREATE OR REPLACE FUNCTION public.uuid_ns_x500()
 RETURNS uuid
 LANGUAGE c
 IMMUTABLE PARALLEL SAFE STRICT
AS '$libdir/uuid-ossp', $function$uuid_ns_x500$function$
;

ALTER TABLE "public".contact ADD CONSTRAINT fk_contact_user FOREIGN KEY ( user_id ) REFERENCES "public"."user"( id );

ALTER TABLE "public".contact ADD CONSTRAINT fk_contact_user_0 FOREIGN KEY ( target_id ) REFERENCES "public"."user"( id );

ALTER TABLE "public".discussion ADD CONSTRAINT fk_discussion_meeting FOREIGN KEY ( meeting_id ) REFERENCES "public".meeting( id );

ALTER TABLE "public".discussionusers ADD CONSTRAINT fk_discussionusers_user FOREIGN KEY ( user_id ) REFERENCES "public"."user"( id );

ALTER TABLE "public".discussionusers ADD CONSTRAINT fk_discussionusers_discussion FOREIGN KEY ( discussion_id ) REFERENCES "public".discussion( id );

ALTER TABLE "public".discussionusers ADD CONSTRAINT fk_discussionusers_invite FOREIGN KEY ( invite_id ) REFERENCES "public".invite( id );

ALTER TABLE "public"."group" ADD CONSTRAINT fk_group_user FOREIGN KEY ( user_id ) REFERENCES "public"."user"( id );

ALTER TABLE "public".groupcontacts ADD CONSTRAINT fk_groupcontacts_contact FOREIGN KEY ( contact_id ) REFERENCES "public".contact( target_id );

ALTER TABLE "public".groupcontacts ADD CONSTRAINT fk_groupcontacts_group FOREIGN KEY ( group_id ) REFERENCES "public"."group"( id );

ALTER TABLE "public".invite ADD CONSTRAINT fk_meetinginvite_user FOREIGN KEY ( user_id ) REFERENCES "public"."user"( id );

ALTER TABLE "public".invite ADD CONSTRAINT fk_meetinginvite_meeting FOREIGN KEY ( meeting_id ) REFERENCES "public".meeting( id );

ALTER TABLE "public".meetingusers ADD CONSTRAINT fk_meetingusers_meeting FOREIGN KEY ( meeting_id ) REFERENCES "public".meeting( id );

ALTER TABLE "public".meetingusers ADD CONSTRAINT fk_meetingusers_user FOREIGN KEY ( user_id ) REFERENCES "public"."user"( id );

ALTER TABLE "public".meetingusers ADD CONSTRAINT fk_meetingusers_meetinginvite FOREIGN KEY ( invite_id ) REFERENCES "public".invite( id );

ALTER TABLE "public".message ADD CONSTRAINT fk_message_user FOREIGN KEY ( user_id ) REFERENCES "public"."user"( id );

ALTER TABLE "public".message ADD CONSTRAINT fk_message_discussion FOREIGN KEY ( discussion_id ) REFERENCES "public".discussion( id );

ALTER TABLE "public".notification ADD CONSTRAINT fk_notification_user FOREIGN KEY ( user_id ) REFERENCES "public"."user"( id );

ALTER TABLE "public".notification ADD CONSTRAINT fk_notification_user_0 FOREIGN KEY ( sender_id ) REFERENCES "public"."user"( id );

ALTER TABLE "public".notification ADD CONSTRAINT fk_notification_meeting FOREIGN KEY ( meeting_id ) REFERENCES "public".meeting( id );

ALTER TABLE "public".notification ADD CONSTRAINT fk_notification_discussion FOREIGN KEY ( discussion_id ) REFERENCES "public".discussion( id );

ALTER TABLE "public"."user" ADD CONSTRAINT fk_user_meetinginvite FOREIGN KEY ( invite_id ) REFERENCES "public".invite( id );

INSERT INTO "public".meeting( id, start_at, end_at, created_at, updated_at, deleted_at, title, password, description, video, audio, locked, locked_at, sharedscreen ) VALUES ( 'ea57f3a7-4d84-4467-8e4a-5bb25bdb956c', '2021-01-30 10.54.13 PM', null, '2021-01-30 10.54.13 PM', null, null, 'Test 1', null, null, true, true, false, null, true);
INSERT INTO "public".meeting( id, start_at, end_at, created_at, updated_at, deleted_at, title, password, description, video, audio, locked, locked_at, sharedscreen ) VALUES ( 'a838834e-75a8-417a-83ab-5fd78fb14226', '2021-01-30 10.54.13 PM', null, '2021-01-30 10.54.13 PM', null, null, 'Test 2', null, null, true, true, false, null, true);
INSERT INTO "public".meetingusers( created_at, updated_at, deleted_at, permissions, meeting_id, user_id, status, invite_id ) VALUES ( '2021-01-30 11.00.00 PM', null, null, '[]', 'a838834e-75a8-417a-83ab-5fd78fb14226', 'ac96735d-aab2-4f06-8d96-4c14fb384ba4', 0, null);
INSERT INTO "public".meetingusers( created_at, updated_at, deleted_at, permissions, meeting_id, user_id, status, invite_id ) VALUES ( '2021-01-30 11.03.41 PM', null, null, '[]', 'ea57f3a7-4d84-4467-8e4a-5bb25bdb956c', 'ac96735d-aab2-4f06-8d96-4c14fb384ba4', 0, null);
INSERT INTO "public"."user"( id, firstname, email, password, created_at, updated_at, avatar, lastname, status, invite_id, phone, fax, address, city, country ) VALUES ( 'ac96735d-aab2-4f06-8d96-4c14fb384ba4', 'corentin', 'truc@gmail.com', '12345', '2021-01-30 10.52.13 PM', '2021-01-30 10.52.13 PM', null, null, 0, null, null, null, null, null, null);
