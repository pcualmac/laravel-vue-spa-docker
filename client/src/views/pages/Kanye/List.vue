<template>
  <div>
    <list-table resource="kanye" api-path="/api/kanye" :sort="{column: 'id', direction: 'asc'}" :filters="filters"
                :columns="columns" :actions="actions">
      <template #cell(permissions_count)="data">
        <b-progress
            :max="totalPermissions"
            striped
            animated
            :class="`progress-bar-${getRoleStrengthVariant((data.item.permissions_count / totalPermissions) * 100)}`"
        >
          <b-progress-bar
              :value="data.item.permissions_count"
              :label="`${parseInt((data.item.permissions_count / totalPermissions) * 100)}%`"
              :variant="getRoleStrengthVariant((data.item.permissions_count / totalPermissions) * 100)"
          />
        </b-progress>
      </template>
    </list-table>

  </div>
</template>

<script>
import ListTable from '../../components/ListTable';
import {SET_BREADCRUMB} from "@/store/breadcrumbs.store";
import {getPermissions, getRoleStrengthVariant} from "@core/comp-functions/roles";

export default {
  components: {
    ListTable,
  },
  data() {
    return {
      actions: {
        view: this.$ability.can('view', 'role'),
        delete: this.$ability.can('delete', 'role'),
      },
    };
  },
  computed: {
    columns() {
      return [
        {key: 'id', label: 'ID', sortable: true},
        {key: 'name', label: 'Name', sortable: true},
        {key: 'permissions_count', label: 'Strength', sortable: false},
        {key: 'created_at', label: 'Created At', sortable: true},
        {key: 'actions'},
      ];
    }
  },
  mounted() {
    this.$store.dispatch(SET_BREADCRUMB, [
      {text: this.$t('modules.roles.roles'), active: true}
    ]);
  },
  setup() {
    const {totalPermissions} = getPermissions();

    return {
      // ...methods
      getRoleStrengthVariant,

      // ...computed
      totalPermissions,

      // ...data
    }
  }
}
</script>