<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Conversations
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */


namespace Twilio\Rest\Conversations\V1\Service\User;

use Twilio\Exceptions\TwilioException;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;
use Twilio\InstanceContext;
use Twilio\Serialize;


class UserConversationContext extends InstanceContext
    {
    /**
     * Initialize the UserConversationContext
     *
     * @param Version $version Version that contains the resource
     * @param string $chatServiceSid The SID of the [Conversation Service](https://www.twilio.com/docs/conversations/api/service-resource) the Conversation resource is associated with.
     * @param string $userSid The unique SID identifier of the [User resource](https://www.twilio.com/docs/conversations/api/user-resource). This value can be either the `sid` or the `identity` of the User resource.
     * @param string $conversationSid The unique SID identifier of the Conversation. This value can be either the `sid` or the `unique_name` of the [Conversation resource](https://www.twilio.com/docs/conversations/api/conversation-resource).
     */
    public function __construct(
        Version $version,
        $chatServiceSid,
        $userSid,
        $conversationSid
    ) {
        parent::__construct($version);

        // Path Solution
        $this->solution = [
        'chatServiceSid' =>
            $chatServiceSid,
        'userSid' =>
            $userSid,
        'conversationSid' =>
            $conversationSid,
        ];

        $this->uri = '/Services/' . \rawurlencode($chatServiceSid)
        .'/Users/' . \rawurlencode($userSid)
        .'/Conversations/' . \rawurlencode($conversationSid)
        .'';
    }

    /**
     * Delete the UserConversationInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete(): bool
    {

        $headers = Values::of(['Content-Type' => 'application/x-www-form-urlencoded' ]);
        return $this->version->delete('DELETE', $this->uri, [], [], $headers);
    }


    /**
     * Fetch the UserConversationInstance
     *
     * @return UserConversationInstance Fetched UserConversationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): UserConversationInstance
    {

        $headers = Values::of(['Content-Type' => 'application/x-www-form-urlencoded' ]);
        $payload = $this->version->fetch('GET', $this->uri, [], [], $headers);

        return new UserConversationInstance(
            $this->version,
            $payload,
            $this->solution['chatServiceSid'],
            $this->solution['userSid'],
            $this->solution['conversationSid']
        );
    }


    /**
     * Update the UserConversationInstance
     *
     * @param array|Options $options Optional Arguments
     * @return UserConversationInstance Updated UserConversationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []): UserConversationInstance
    {

        $options = new Values($options);

        $data = Values::of([
            'NotificationLevel' =>
                $options['notificationLevel'],
            'LastReadTimestamp' =>
                Serialize::iso8601DateTime($options['lastReadTimestamp']),
            'LastReadMessageIndex' =>
                $options['lastReadMessageIndex'],
        ]);

        $headers = Values::of(['Content-Type' => 'application/x-www-form-urlencoded' ]);
        $payload = $this->version->update('POST', $this->uri, [], $data, $headers);

        return new UserConversationInstance(
            $this->version,
            $payload,
            $this->solution['chatServiceSid'],
            $this->solution['userSid'],
            $this->solution['conversationSid']
        );
    }


    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Conversations.V1.UserConversationContext ' . \implode(' ', $context) . ']';
    }
}
